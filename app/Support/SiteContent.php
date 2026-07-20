<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class SiteContent
{
    private const MAX_BACKUPS = 20;

    public static function path(): string
    {
        return storage_path('app/site-content.json');
    }

    public static function backupDirectory(): string
    {
        return storage_path('app/site-content-backups');
    }

    public static function get(): array
    {
        $defaults = self::defaults();
        $path = self::path();

        if (! File::exists($path)) {
            return $defaults;
        }

        $saved = json_decode((string) File::get($path), true);

        if (! is_array($saved)) {
            return $defaults;
        }

        return array_replace_recursive($defaults, $saved);
    }

    public static function save(array $content): void
    {
        self::backupCurrent();

        File::ensureDirectoryExists(dirname(self::path()));
        File::put(
            self::path(),
            json_encode(self::sanitize($content), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }

    public static function backupCurrent(): ?string
    {
        $path = self::path();

        if (! File::exists($path)) {
            return null;
        }

        File::ensureDirectoryExists(self::backupDirectory());

        do {
            $backupPath = self::backupDirectory().'/site-content-'.now()->format('Y-m-d-H-i-s').'.json';

            if (File::exists($backupPath)) {
                usleep(100000);
            }
        } while (File::exists($backupPath));

        File::copy($path, $backupPath);
        self::pruneBackups();

        return $backupPath;
    }

    public static function backups(): array
    {
        if (! File::isDirectory(self::backupDirectory())) {
            return [];
        }

        $current = self::get();

        return collect(File::files(self::backupDirectory()))
            ->filter(fn ($file): bool => str_starts_with($file->getFilename(), 'site-content-') && $file->getExtension() === 'json')
            ->sortByDesc(fn ($file): int => $file->getMTime())
            ->map(function ($file) use ($current): array {
                $backupContent = json_decode((string) File::get($file->getPathname()), true);
                $backupContent = is_array($backupContent) ? array_replace_recursive(self::defaults(), $backupContent) : [];

                return [
                    'name' => $file->getFilename(),
                    'path' => $file->getPathname(),
                    'date' => date('Y.m.d. H:i:s', $file->getMTime()),
                    'summary' => self::backupSummary($backupContent, $current),
                ];
            })
            ->values()
            ->all();
    }

    public static function restore(string $filename): bool
    {
        $safeName = basename($filename);

        if (! preg_match('/^site-content-\d{4}-\d{2}-\d{2}-\d{2}-\d{2}-\d{2}\.json$/', $safeName)) {
            return false;
        }

        $backupPath = self::backupDirectory().'/'.$safeName;

        if (! File::exists($backupPath)) {
            return false;
        }

        $decoded = json_decode((string) File::get($backupPath), true);

        if (! is_array($decoded)) {
            return false;
        }

        self::backupCurrent();
        File::ensureDirectoryExists(dirname(self::path()));
        File::copy($backupPath, self::path());
        self::pruneBackups();

        return true;
    }

    private static function pruneBackups(): void
    {
        collect(self::backups())
            ->slice(self::MAX_BACKUPS)
            ->each(fn (array $backup): bool => File::delete($backup['path']));
    }

    private static function backupSummary(array $backup, array $current): array
    {
        $summary = [];

        self::appendSectionSummary($summary, 'Szolgáltatások', Arr::get($backup, 'services', []), Arr::get($current, 'services', []));
        self::appendSectionSummary($summary, 'Galéria', Arr::get($backup, 'gallery', []), Arr::get($current, 'gallery', []));
        self::appendPriceSummary($summary, Arr::get($backup, 'price_groups', []), Arr::get($current, 'price_groups', []));
        self::appendSectionSummary($summary, 'Előnyök', Arr::get($backup, 'benefits', []), Arr::get($current, 'benefits', []));
        self::appendSectionSummary($summary, 'Vélemények', Arr::get($backup, 'testimonials', []), Arr::get($current, 'testimonials', []));
        self::appendSectionSummary($summary, 'GYIK', Arr::get($backup, 'faq', []), Arr::get($current, 'faq', []));

        return $summary === []
            ? ['Nincs lényeges eltérés a jelenlegi tartalomhoz képest.']
            : array_slice($summary, 0, 7);
    }

    private static function appendSectionSummary(array &$summary, string $label, array $backupRows, array $currentRows): void
    {
        $backupCount = count($backupRows);
        $currentCount = count($currentRows);
        $changed = self::changedRows($backupRows, $currentRows);

        if ($backupCount !== $currentCount) {
            $summary[] = "{$label}: {$backupCount} sor volt, most {$currentCount} sor van.";

            return;
        }

        if ($changed > 0) {
            $summary[] = "{$label}: {$changed} sor szövege vagy képe eltér.";
        }
    }

    private static function appendPriceSummary(array &$summary, array $backupGroups, array $currentGroups): void
    {
        $backupGroupCount = count($backupGroups);
        $currentGroupCount = count($currentGroups);
        $backupItemCount = self::priceItemCount($backupGroups);
        $currentItemCount = self::priceItemCount($currentGroups);

        if ($backupGroupCount !== $currentGroupCount || $backupItemCount !== $currentItemCount) {
            $summary[] = "Árlista: {$backupGroupCount} csoport / {$backupItemCount} sor volt, most {$currentGroupCount} csoport / {$currentItemCount} sor van.";

            return;
        }

        $changed = self::changedRows(self::flattenPriceRows($backupGroups), self::flattenPriceRows($currentGroups));

        if ($changed > 0) {
            $summary[] = "Árlista: {$changed} ár vagy szolgáltatásnév eltér.";
        }
    }

    private static function changedRows(array $backupRows, array $currentRows): int
    {
        $max = max(count($backupRows), count($currentRows));
        $changed = 0;

        for ($index = 0; $index < $max; $index++) {
            $backupHash = json_encode($backupRows[$index] ?? null, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $currentHash = json_encode($currentRows[$index] ?? null, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            if ($backupHash !== $currentHash) {
                $changed++;
            }
        }

        return $changed;
    }

    private static function priceItemCount(array $groups): int
    {
        return collect($groups)
            ->sum(fn (array $group): int => count(Arr::get($group, 'items', [])));
    }

    private static function flattenPriceRows(array $groups): array
    {
        return collect($groups)
            ->flatMap(function (array $group): array {
                $groupTitle = trim((string) Arr::get($group, 'title', ''));

                return collect(Arr::get($group, 'items', []))
                    ->map(fn (array $item): array => [
                        'group' => $groupTitle,
                        'name' => trim((string) Arr::get($item, 'name', '')),
                        'price' => trim((string) Arr::get($item, 'price', '')),
                    ])
                    ->all();
            })
            ->values()
            ->all();
    }

    public static function sanitize(array $content): array
    {
        return [
            'services' => self::rows(Arr::get($content, 'services', []), ['title', 'description', 'price', 'image', 'alt']),
            'gallery' => self::rows(Arr::get($content, 'gallery', []), ['image', 'alt']),
            'price_groups' => collect(Arr::get($content, 'price_groups', []))
                ->map(function ($group): array {
                    return [
                        'title' => trim((string) Arr::get($group, 'title', '')),
                        'items' => self::rows(Arr::get($group, 'items', []), ['name', 'price']),
                    ];
                })
                ->filter(fn (array $group): bool => $group['title'] !== '' || count($group['items']) > 0)
                ->values()
                ->all(),
            'benefits' => self::rows(Arr::get($content, 'benefits', []), ['title', 'description']),
            'testimonials' => self::rows(Arr::get($content, 'testimonials', []), ['quote', 'name']),
            'faq' => self::rows(Arr::get($content, 'faq', []), ['question', 'answer']),
        ];
    }

    private static function rows(array $rows, array $fields): array
    {
        return collect($rows)
            ->map(function ($row) use ($fields): array {
                return collect($fields)
                    ->mapWithKeys(fn (string $field): array => [$field => trim((string) Arr::get($row, $field, ''))])
                    ->all();
            })
            ->filter(function (array $row): bool {
                return collect($row)->filter(fn (string $value): bool => $value !== '')->isNotEmpty();
            })
            ->values()
            ->all();
    }

    public static function defaults(): array
    {
        return [
            'services' => [
                [
                    'title' => 'Műszempilla építés UV-LED technológiával',
                    'description' => 'Szemformához és stílushoz igazított, tartós és kényelmes viselet a természetes vagy hangsúlyosabb tekintetért.',
                    'price' => '14 000 Ft-tól',
                    'image' => 'assets/services/2d-volume.png',
                    'alt' => 'Műszempilla építés közeli képe',
                ],
                [
                    'title' => 'Szempilla lifting',
                    'description' => 'Saját pilláid elegáns ívelése természetes, ápolt hatással, épített szempilla nélkül.',
                    'price' => '10 000 Ft-tól',
                    'image' => 'assets/services/classic.png',
                    'alt' => 'Természetes szempilla lifting hatás',
                ],
                [
                    'title' => 'Szemöldök laminálás',
                    'description' => 'Rendezett, dúsabb hatású szemöldök, amely szépen keretezi az arcot és könnyen formázható marad.',
                    'price' => '11 000 Ft-tól',
                    'image' => 'assets/services/3d-volume.png',
                    'alt' => 'Szemöldök laminálás hangulatkép',
                ],
                [
                    'title' => 'Szemöldök styling és festés',
                    'description' => 'Finom formaigazítás, szedés és festés a harmonikusabb, ápolt összhatásért.',
                    'price' => '2 000 Ft-tól',
                    'image' => 'assets/hero-eye-photo.png',
                    'alt' => 'Szemöldök styling és festés hangulatkép',
                ],
                [
                    'title' => 'AirBrush Brow System',
                    'description' => 'Modern, porlasztásos technológia soft powder, ombre vagy intenzívebb szemöldökhatáshoz.',
                    'price' => 'Ajánlatkérés alapján',
                    'image' => 'assets/intro.jpg',
                    'alt' => 'AirBrush Brow System szépségszalon hangulatkép',
                ],
            ],
            'price_groups' => [
                [
                    'title' => 'Műszempilla',
                    'items' => [
                        ['name' => '2D-3D hibrid műszempilla építés', 'price' => '14 000 Ft'],
                        ['name' => '2D-3D hibrid műszempilla töltés, 21 napig', 'price' => '11 000 Ft'],
                        ['name' => '3D-4D műszempilla építés', 'price' => '16 000 Ft'],
                        ['name' => '3D-4D műszempilla töltés, 21 napig', 'price' => '13 000 Ft'],
                        ['name' => '5D-6D műszempilla építés', 'price' => '18 000 Ft'],
                        ['name' => '5D-6D műszempilla töltés, 21 napig', 'price' => '15 000 Ft'],
                        ['name' => 'Színes szempilla', 'price' => '2 000 Ft'],
                        ['name' => 'Műszempilla leoldás', 'price' => '3 000 Ft'],
                    ],
                ],
                [
                    'title' => 'Lifting és festés',
                    'items' => [
                        ['name' => 'Szempilla lifting festéssel', 'price' => '11 000 Ft'],
                        ['name' => 'Szempilla lifting festés nélkül', 'price' => '10 000 Ft'],
                        ['name' => 'Szempilla festés', 'price' => '2 500 Ft'],
                    ],
                ],
                [
                    'title' => 'Szemöldök styling',
                    'items' => [
                        ['name' => 'Szemöldök laminálás festéssel', 'price' => '12 000 Ft'],
                        ['name' => 'Szemöldök laminálás festés nélkül', 'price' => '11 000 Ft'],
                        ['name' => 'Szemöldök szedés csipesszel', 'price' => '2 000 Ft'],
                        ['name' => 'Szemöldök szedés gyantával', 'price' => '2 500 Ft'],
                        ['name' => 'Szemöldök festés', 'price' => '2 500 Ft'],
                        ['name' => 'Bajuszgyanta', 'price' => '2 500 Ft'],
                    ],
                ],
            ],
            'gallery' => [
                ['image' => 'assets/services/2d-volume.png', 'alt' => 'Finom műszempilla styling közeli képe'],
                ['image' => 'assets/services/3d-volume.png', 'alt' => 'Elegáns volume műszempilla eredmény'],
                ['image' => 'assets/services/classic.png', 'alt' => 'Természetes hatású szempilla styling'],
                ['image' => 'assets/intro.jpg', 'alt' => 'Brill Lash and Beauty szalon hangulatkép'],
            ],
            'benefits' => [
                ['title' => 'Személyre szabott tervezés', 'description' => 'A formát az arcodhoz, szemformádhoz és a hétköznapi stílusodhoz igazítom.'],
                ['title' => 'Prémium alapanyagok', 'description' => 'Professzionális termékekkel és modern technológiákkal dolgozom az igényes végeredményért.'],
                ['title' => 'Precíz, nyugodt munka', 'description' => 'A kezelés alatt figyelmet, türelmet és barátságos környezetet kapsz.'],
                ['title' => 'Részletes konzultáció', 'description' => 'Átbeszéljük az elképzelésedet, az otthoni ápolást és a számodra ideális ritmust.'],
                ['title' => 'Modern technológiák', 'description' => 'UV-LED műszempilla építés és AirBrush Brow System is elérhető a szalonban.'],
                ['title' => 'Ápolási tanácsok', 'description' => 'A tartós, szép eredményhez minden fontos tudnivalót érthetően elmondok.'],
            ],
            'testimonials' => [
                ['quote' => 'Ide kerülhet egy valódi vendégvélemény, ha már rendelkezésre áll engedéllyel.', 'name' => 'Helyőrző vélemény'],
                ['quote' => 'Rövid, hiteles értékelés kerülhet ide a szolgáltatásról vagy a szalonélményről.', 'name' => 'Instagram üzenet'],
                ['quote' => 'A végleges szöveg legyen pontos, valós és a vendég hozzájárulásával megjelenítve.', 'name' => 'Visszatérő vendég'],
            ],
            'faq' => [
                ['question' => 'Mennyi ideig tart egy műszempilla építés?', 'answer' => 'A választott technikától függően általában 2-3 órát vesz igénybe.'],
                ['question' => 'Mikor ajánlott töltésre érkezni?', 'answer' => 'Általában 3-4 hetente ajánlott, de ez egyénfüggő. A töltési ár legfeljebb 21 napig érvényes.'],
                ['question' => 'Érheti víz az UV-LED technológiával készült műszempillát?', 'answer' => 'Igen, a ragasztó azonnal megköt, ezért a pillát a kezelés után víz és gőz is érheti.'],
                ['question' => 'Kontaktlencsében érkezhetek?', 'answer' => 'Kérlek, a kezelés előtt vedd ki a kontaktlencsét, hogy kényelmes és biztonságos legyen a folyamat.'],
                ['question' => 'Hogyan készüljek a kezelésre?', 'answer' => 'A tökéletes eredmény elérése érdekében kérlek, hogy smink nélkül érkezz.'],
                ['question' => 'Mikor nem végezhető el a kezelés?', 'answer' => 'Aktív szemkörnyéki irritáció, fertőzés vagy erős érzékenység esetén a kezelést későbbi időpontra halasztjuk.'],
                ['question' => 'Hogyan foglalhatok időpontot?', 'answer' => 'Instagram üzenetben vagy telefonon tudsz időpontot kérni.'],
            ],
        ];
    }
}

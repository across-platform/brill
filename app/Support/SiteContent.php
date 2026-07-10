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

        return collect(File::files(self::backupDirectory()))
            ->filter(fn ($file): bool => str_starts_with($file->getFilename(), 'site-content-') && $file->getExtension() === 'json')
            ->sortByDesc(fn ($file): int => $file->getMTime())
            ->map(fn ($file): array => [
                'name' => $file->getFilename(),
                'path' => $file->getPathname(),
                'date' => date('Y.m.d. H:i:s', $file->getMTime()),
            ])
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
                    'title' => '2D volume',
                    'description' => 'Könnyedebb dúsítás, elegáns mindennapi viselethez.',
                    'price' => '14 000 Ft',
                    'image' => 'assets/services/2d-volume.png',
                    'alt' => '2D volume szempilla szett közeli képe',
                ],
                [
                    'title' => '3D volume',
                    'description' => 'Látványosabb, mégis finom hatás azoknak, akik szeretik a hangsúlyt.',
                    'price' => '16 000 Ft',
                    'image' => 'assets/services/3d-volume.png',
                    'alt' => '3D volume szempilla szett közeli képe',
                ],
            ],
            'price_groups' => [
                [
                    'title' => 'Műszempilla',
                    'items' => [
                        ['name' => '2D műszempilla építés', 'price' => '14 000 Ft'],
                        ['name' => '2D műszempilla töltés (21 napig)', 'price' => '11 000 Ft'],
                        ['name' => '3D-4D műszempilla építés', 'price' => '16 000 Ft'],
                        ['name' => '3D-4D műszempilla töltés (21 napig)', 'price' => '13 000 Ft'],
                        ['name' => '5D-6D műszempilla építés', 'price' => '18 000 Ft'],
                        ['name' => '5D-6D műszempilla töltés (21 napig)', 'price' => '15 000 Ft'],
                        ['name' => 'Színes szempilla', 'price' => '1 500 Ft'],
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
                    'title' => 'Szemöldök és gyanta',
                    'items' => [
                        ['name' => 'Szemöldök laminálás festéssel', 'price' => '12 000 Ft'],
                        ['name' => 'Szemöldök laminálás festés nélkül', 'price' => '11 000 Ft'],
                        ['name' => 'Szemöldök szedés csipesszel', 'price' => '2 000 Ft'],
                        ['name' => 'Szemöldök szedés gyantával', 'price' => '2 500 Ft'],
                        ['name' => 'Szemöldök festés', 'price' => '2 500 Ft'],
                        ['name' => 'Bajusz gyanta', 'price' => '2 500 Ft'],
                    ],
                ],
            ],
            'gallery' => [
                ['image' => 'assets/services/2d-volume.png', 'alt' => 'Finom 2D styling közeli képe'],
                ['image' => 'assets/services/3d-volume.png', 'alt' => 'Elegáns volume eredmény'],
                ['image' => 'assets/services/classic.png', 'alt' => 'Természetes hatású styling'],
            ],
            'benefits' => [
                ['title' => 'Egyedi styling', 'description' => 'A formát a szemformádhoz, stílusodhoz és természetes adottságaidhoz igazítom.'],
                ['title' => 'Prémium anyagok', 'description' => 'Gondosan válogatott, professzionális termékekkel dolgozom a tartós és kényelmes viseletért.'],
                ['title' => 'Folyamatos fejlődés', 'description' => '2018 óta képzem magam, hogy mindig naprakész technikákkal dolgozhassak.'],
                ['title' => 'Nyugodt környezet', 'description' => 'Az itt töltött idő nem csak szépülés, hanem egy kis kikapcsolódás is.'],
            ],
            'testimonials' => [
                ['quote' => 'Gyönyörű, természetes hatás. Pont olyan lett, amilyet szerettem volna.', 'name' => 'Laura'],
                ['quote' => 'Nagyon precíz munka és kellemes hangulat. A pilláim hetekig szépek maradtak.', 'name' => 'Niki'],
                ['quote' => 'Eszti kedves, figyelmes, és minden alkalommal rám szabja a szettet.', 'name' => 'Dóri'],
            ],
            'faq' => [
                ['question' => 'Fáj a szempillaépítés?', 'answer' => 'Nem, a kezelés fájdalommentes. A legtöbb vendég számára inkább pihentető élmény.'],
                ['question' => 'Mennyi ideig tart egy új építés?', 'answer' => 'A választott technikától függően általában 2-3 órát vesz igénybe.'],
                ['question' => 'Mikor szükséges a töltés?', 'answer' => 'Általában 3 hetente ajánlott, hogy a szett szép és egyenletes maradjon.'],
                ['question' => 'Sminkelhetem a pilláimat?', 'answer' => 'A tökéletes eredmény elérése érdekében kérlek, hogy smink nélkül érkezz.'],
                ['question' => 'Előfordulhat allergiás reakció?', 'answer' => 'Ritkán igen. Ez egyéni érzékenységtől függ, ezért minden fontos tudnivalót átbeszélünk a kezelés előtt.'],
            ],
        ];
    }
}

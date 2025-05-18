<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Translator
{
    /**
     * Flatten a mixed structure (model/array/object) down to a flat list
     * of string leaves, preserving their “dot-path” keys.
     *
     * @param  mixed  $data
     * @param  string $path  (used internally for recursion)
     * @param  array  $out   (collects path => string)
     * @return array<string,string>
     */
    public function flattenStrings($data, string $path = '', array &$out = []): array
    {
        if (is_string($data)) {
            $out[$path] = $data;
        } elseif (is_array($data)) {
            foreach ($data as $k => $v) {
                $this->flattenStrings($v, $path === '' ? $k : "{$path}.{$k}", $out);
            }
        } elseif (is_object($data)) {
            foreach (get_object_vars($data) as $k => $v) {
                $this->flattenStrings($v, $path === '' ? $k : "{$path}.{$k}", $out);
            }
        }
        // ignore ints, bools, nulls...
        return $out;
    }

    /**
     * Take your flat path=>string map and do a single batch translation,
     * then return a path=>translatedString map.
     */
    public function translateBatchStrings(array $pathToStrings, string $source, string $target): array
    {
        $originals = array_values($pathToStrings);
        if (empty($originals)) {
            return [];
        }

        $joined = implode(" ||| ", $originals);

        try {
            $res = Http::withToken(config('services.openrouter.key'))
                ->timeout(60)
                ->post(config('services.openrouter.url'), [
                    'model'    => config('services.openrouter.model'),
                    'stream'   => false,
                    'messages' => [
                        [
                            'role'    => 'system',
                            'content' => "Translate from {$source} to {$target}. Return only the translated text, preserving the '|||' delimiter."
                        ],
                        [
                            'role'    => 'user',
                            'content' => $joined,
                        ],
                    ],
                ]);

            if ($res->ok()) {
                $translatedJoined = trim($res->json('choices.0.message.content'));
                $translatedArray  = explode(' ||| ', $translatedJoined);

                if (count($translatedArray) === count($originals)) {
                    // re‑map: path => translated
                    return array_combine(array_keys($pathToStrings), $translatedArray);
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Batch translate failed: " . $e->getMessage());
        }

        // fallback to original strings
        return $pathToStrings;
    }

    /**
     * Walk your original data and replace each string leaf
     * with the translated string from $translations[path].
     */
    public function applyTranslations($data, array $translations, string $path = '')
    {
        if (is_string($data) && isset($translations[$path])) {
            return $translations[$path];
        }
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $subPath = $path === '' ? $k : "{$path}.{$k}";
                $data[$k] = $this->applyTranslations($v, $translations, $subPath);
            }
            return $data;
        }
        if (is_object($data)) {
            foreach (get_object_vars($data) as $k => $v) {
                $subPath = $path === '' ? $k : "{$path}.{$k}";
                $data->{$k} = $this->applyTranslations($v, $translations, $subPath);
            }
            return $data;
        }
        return $data;
    }
}

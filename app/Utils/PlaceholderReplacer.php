<?php


namespace App\Utils;
class PlaceholderReplacer
{
    /**
     * Replace placeholders in template with values from root object
     * Supports nested paths like {company.employees[0].name}
     */
    public function replacePlaceholders(string $template, array|object $rootObject): string
    {
        $pattern = '/\{([^{}]+)}/';

        return preg_replace_callback($pattern, function ($matches) use ($rootObject) {
            $path = $matches[1];
            $value = $this->resolvePath($path, $rootObject);
            return $value !== null ? (string)$value : '';
        }, $template);
    }

    /**
     * Resolve a dotted path in nested array/object structure
     * Handles:
     * - Simple keys: {company.name}
     * - Array indices: {[0]}
     * - Map with array: {employees[0].name}
     * - Mixed: {company.employees[0].department.manager.name}
     */
    private function resolvePath(string $path, array|object $current): mixed
    {
        $parts = explode('.', $path);

        foreach ($parts as $part) {
            if ($current === null) {
                return null;
            }

            // Handle array index like [0]
            if (preg_match('/^\[\d+]$/', $part)) {
                $index = (int)preg_replace('/[\[\]]/', '', $part);

                if (is_array($current) && isset($current[$index])) {
                    $current = $current[$index];
                } else {
                    return null;
                }
            } // Handle map/array with index like "employees[0]"
            elseif (preg_match('/^(.+)\[\d+]$/', $part, $matches)) {
                $key = $matches[1];
                $index = (int)preg_replace('/\D/', '', substr($part, strlen($key)));

                $mapValue = $this->getValueFromCurrent($current, $key);

                if (is_array($mapValue) && isset($mapValue[$index])) {
                    $current = $mapValue[$index];
                } else {
                    return null;
                }
            } // Normal key access
            else {
                $current = $this->getValueFromCurrent($current, $part);

                if ($current === null) {
                    return null;
                }
            }
        }

        return $current;
    }

    /**
     * Get value from array or object by key
     */
    private function getValueFromCurrent(array|object $current, string $key): mixed
    {
        if (is_array($current)) {
            return $current[$key] ?? null;
        } elseif (is_object($current)) {
            return $current->$key ?? null;
        }

        return null;
    }
}

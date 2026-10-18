<?php

namespace Grav\Plugin\LicenseManager;

use Grav\Common\Data\Blueprint;
use Grav\Common\Data\Data;
use Grav\Common\GPM\Licenses;

/**
 * Class Controller
 * @package Grav\Plugin\LicenseManager
 */
class LicenseManager
{

    protected static $data;

    protected static $blueprint = 'plugin://license-manager/admin/blueprints/licenses.yaml';

    /**
     * Load License Data object statically
     *
     * @return Data
     */
    public static function load()
    {
        if (!self::$data) {
            $blueprint = new Blueprint(self::$blueprint);
            $blueprint->load();
            $file = Licenses::getLicenseFile();
            $licenses = self::normalize(Licenses::get());

            $obj = new Data(['licenses' => $licenses], $blueprint);
            $obj->file($file);

            self::$data = $obj;
        }
        return self::$data;
    }

    /**
     * Trim and lowercase every slug and trim every key, dropping entries
     * left without a slug. A key pasted against " lightbox-gallery" is
     * otherwise filed under a slug no package has, and the install fails.
     *
     * @param mixed $licenses slug => key
     * @return array<string, mixed>
     */
    public static function normalize($licenses): array
    {
        if (!is_array($licenses)) {
            return [];
        }

        $normalized = [];
        foreach ($licenses as $slug => $license) {
            $slug = strtolower(trim((string) $slug));
            if ($slug === '') {
                continue;
            }
            $normalized[$slug] = is_string($license) ? trim($license) : $license;
        }

        return $normalized;
    }
}

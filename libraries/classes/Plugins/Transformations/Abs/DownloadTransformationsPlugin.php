<?php
/**
 * Abstract class for the download transformations plugins
 */

declare(strict_types=1);

namespace PhpMyAdmin\Plugins\Transformations\Abs;

use PhpMyAdmin\FieldMetadata;
use PhpMyAdmin\Plugins\TransformationsPlugin;
use PhpMyAdmin\Url;

use function __;
use function array_merge;
use function htmlspecialchars;

/**
 * Provides common methods for all of the download transformations plugins.
 */
abstract class DownloadTransformationsPlugin extends TransformationsPlugin
{
    /**
     * Gets the transformation description of the specific plugin
     */
    public static function getInfo(): string
    {
        return __(
            'Displays a link to download the binary data of the column. You can'
            . ' use the first option to specify the filename, or use the second'
            . ' option as the name of a column which contains the filename. If'
            . ' you use the second option, you need to set the first option to'
            . ' the empty string.',
        );
    }

    /**
     * Does the actual work of each specific transformations plugin.
     *
     * @param string             $buffer  text to be transformed
     * @param array              $options transformation options
     * @param FieldMetadata|null $meta    meta information
     */
    public function applyTransformation($buffer, array $options = [], FieldMetadata|null $meta = null): string
    {
        $GLOBALS['row'] ??= null;
        $GLOBALS['fields_meta'] ??= null;

        if (isset($options[0]) && ! empty($options[0])) {
            $cn = $options[0]; // filename
        } else {
            if (isset($options[1]) && ! empty($options[1])) {
                foreach ($GLOBALS['fields_meta'] as $key => $val) {
                    if ($val->name == $options[1]) {
                        $pos = $key;
                        break;
                    }
                }

                if (isset($pos)) {
                    $cn = $GLOBALS['row'][$pos];
                }
            }

            if (empty($cn)) {
                $cn = 'binary_file.dat';
            }
        }

        $link = '<a href="' . Url::getFromRoute(
            '/transformation/wrapper',
            array_merge($options['wrapper_params'], [
                'ct' => 'application/octet-stream',
                'cn' => $cn,
            ]),
        );
        $link .= '" title="' . htmlspecialchars($cn);
        $link .= '" class="disableAjax">' . htmlspecialchars($cn);
        $link .= '</a>';

        return $link;
    }

    /* ~~~~~~~~~~~~~~~~~~~~ Getters and Setters ~~~~~~~~~~~~~~~~~~~~ */

    /**
     * Gets the transformation name of the specific plugin
     */
    public static function getName(): string
    {
        return 'Download';
    }
}

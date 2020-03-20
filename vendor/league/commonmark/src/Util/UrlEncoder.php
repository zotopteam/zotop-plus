<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * Original code based on the CommonMark JS reference parser (https://bitly.com/commonmark-js)
 *  - (c) John MacFarlane
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Util;

final class UrlEncoder
{
<<<<<<< HEAD
    protected static $dontEncode = [
        '%21' => '!',
        '%23' => '#',
        '%24' => '$',
        '%26' => '&',
        '%27' => '\'',
        '%28' => '(',
        '%29' => ')',
        '%2A' => '*',
        '%2B' => '+',
        '%2C' => ',',
        '%2D' => '-',
        '%2E' => '.',
        '%2F' => '/',
        '%3A' => ':',
        '%3B' => ';',
        '%3D' => '=',
        '%3F' => '?',
        '%40' => '@',
        '%5F' => '_',
        '%7E' => '~',
    ];

    protected static $dontDecode = [
        '%3B' => ';',
        '%2F' => '/',
        '%3F' => '?',
        '%3A' => ':',
        '%40' => '@',
        '%26' => '&',
        '%3D' => '=',
        '%2B' => '+',
        '%24' => '$',
        '%2C' => ',',
        '%23' => '#',
    ];
=======
    private static $encodeCache = ['%00', '%01', '%02', '%03', '%04', '%05', '%06', '%07', '%08', '%09', '%0A', '%0B', '%0C', '%0D', '%0E', '%0F', '%10', '%11', '%12', '%13', '%14', '%15', '%16', '%17', '%18', '%19', '%1A', '%1B', '%1C', '%1D', '%1E', '%1F', '%20', '!', '%22', '#', '$', '%25', '&', "'", '(', ')', '*', '+', ',', '-', '.', '/', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ':', ';', '%3C', '=', '%3E', '?', '@', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '%5B', '%5C', '%5D', '%5E', '_', '%60', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '%7B', '%7C', '%7D', '~', '%7F'];
>>>>>>> feature/v2

    /**
     * @param string $uri
     *
     * @return string
     */
    public static function unescapeAndEncode(string $uri): string
    {
<<<<<<< HEAD
        $decoded = \html_entity_decode($uri);

        return self::encode(self::decode($decoded));
    }

    /**
     * Decode a percent-encoded URI
     *
     * @param string $uri
     *
     * @return string
     */
    private static function decode(string $uri): string
    {
        /** @var string $ret */
        $ret = \preg_replace_callback('/((?:%[0-9a-f]{2})+)/iu', function ($matches) {
            $bytes = \hex2bin(\str_replace('%', '', $matches[1]));

            // Invalid UTF-8 sequences should be kept as-is
            if ($bytes === false || !\mb_check_encoding($bytes, 'UTF-8')) {
                return \strtoupper($matches[0]);
            }

            // Otherwise, split the sequence into characters and decode them (unless that character shouldn't be decoded)
            /** @var string[] $characters */
            $characters = \preg_split('//u', $bytes, -1, \PREG_SPLIT_NO_EMPTY);

            $ret = '';
            foreach ($characters as $char) {
                if (($encoding = \array_search($char, self::$dontDecode, true)) !== false) {
                    $ret .= $encoding;
                } else {
                    $ret .= $char;
                }
            }

            return $ret;
        }, $uri);

        return $ret;
    }

    /**
     * Encode a URI, preserving already-encoded and excluded characters
     *
     * @param string $uri
     *
     * @return string
     */
    private static function encode(string $uri): string
    {
        /** @var string $ret */
        $ret = \preg_replace_callback('/(%[0-9a-f]{2})|./isu', function ($matches) {
            // Keep already-encoded characters as-is
            if (\count($matches) > 1) {
                return $matches[0];
            }

            // Keep excluded characters as-is
            if (\in_array($matches[0], self::$dontEncode)) {
                return $matches[0];
            }

            // Otherwise, encode the character
            return \rawurlencode($matches[0]);
        }, $uri);

        return $ret;
=======
        $result = '';

        /** @var string[] $chars */
        $chars = \preg_split('//u', $uri, -1, \PREG_SPLIT_NO_EMPTY);
        $l = \count($chars);
        for ($i = 0; $i < $l; $i++) {
            $code = $chars[$i];
            if ($code === '%' && $i + 2 < $l) {
                if (\preg_match('/^[0-9a-f]{2}$/i', $chars[$i + 1] . $chars[$i + 2]) === 1) {
                    $result .= '%' . $chars[$i + 1] . $chars[$i + 2];
                    $i += 2;
                    continue;
                }
            }

            if (\ord($code) < 128) {
                $result .= self::$encodeCache[\ord($code)];
                continue;
            }

            $result .= \rawurlencode($code);
        }

        return $result;
>>>>>>> feature/v2
    }
}

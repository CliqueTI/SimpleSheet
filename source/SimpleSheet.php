<?php

namespace CliqueTI\SimpleSheet;

use CliqueTI\SimpleSheet\Enums\ReaderOptions;
use CliqueTI\SimpleSheet\Enums\ReaderType;
use CliqueTI\SimpleSheet\Exceptions\UnsupportedReaderOptionException;
use CliqueTI\SimpleSheet\Exceptions\UnsupportedTypeException;

abstract class SimpleSheet {

    /**
     * READ SHEET FROM FILE
     * @param string $file
     * @param ReaderType|null $type
     * @return SimpleSheetReader
     */
    public static function read(string $file, ?ReaderType $type = null) {
        return new SimpleSheetReader($file,$type);
    }

    /**
     * DEFINE TYPE BY EXTENSION
     * @param string $file
     * @return ReaderType
     * @throws UnsupportedTypeException
     */
    protected function readerTypeFromFile(string $file) {

        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        return match ($extension) {
            'csv' => ReaderType::CSV,
            'ods' => ReaderType::ODS,
            'xlsx' => ReaderType::XLSX,
            default => throw new UnsupportedTypeException(
                "Unsupported spreadsheet type. Supported extensions are .csv,.ods and .xlsx")
        };

    }

    /**
     * DEFINE OPTIONS BY READER TYPE
     * @param ReaderType $type
     * @return ReaderOptions
     * @throws UnsupportedReaderOptionException
     */
    protected function readerOption(ReaderType $type) {

        return match ($type) {
            ReaderType::CSV => ReaderOptions::CSV,
            ReaderType::ODS => ReaderOptions::ODS,
            ReaderType::XLSX => ReaderOptions::XLSX,
            default => throw new UnsupportedReaderOptionException('Unsupported option'),
        };
        
    }
    
}
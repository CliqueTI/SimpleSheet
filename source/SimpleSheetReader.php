<?php

namespace CliqueTI\SimpleSheet;

use CliqueTI\SimpleSheet\Enums\ReaderType;
use CliqueTI\SimpleSheet\Exceptions\UnsupportedReaderOptionException;
use CliqueTI\SimpleSheet\Exceptions\UnsupportedTypeException;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Reader\SheetInterface;

class SimpleSheetReader extends SimpleSheet {

    private string $file;
    private ReaderType $readerType;
    private bool $hasHeaderRow = true;
    private string $sheetName = "";
    private int $sheetNumber = 1;
    private bool $parseFormulas = true;

    private \OpenSpout\Reader\CSV\Options |
            \OpenSpout\Reader\ODS\Options |
            \OpenSpout\Reader\XLSX\Options
            $options;

    private \OpenSpout\Reader\CSV\Reader |
            \OpenSpout\Reader\ODS\Reader |
            \OpenSpout\Reader\XLSX\Reader
            $reader;

    /**
     * @throws UnsupportedReaderOptionException
     * @throws UnsupportedTypeException
     */
    public function __construct(string $file, ?ReaderType $type = null) {
        $this->file = $file;
        $this->readerType = ($type ?: $this->readerTypeFromFile($file));
        $this->options = new (($this->readerOption($this->readerType))->value);
    }

    public function options()  {
        return $this->options;
    }

    public function getReaderType() {
        return $this->readerType;
    }

    public function withoutHeaderRow(): self {
        $this->hasHeaderRow = false;
        return $this;
    }

    public function withoutParseFormulas(): self {
        $this->parseFormulas = false;
        return $this;
    }

    public function sheetName(?string $sheet): self {
        $this->sheetName = $sheet ?: "";
        return $this;
    }

    public function sheetNumber(?int $sheet) {
        $this->sheetNumber = $sheet ?: 1;
        $this->sheetName = "";
    }

    public function getHeaders(): ?array {

        $sheet = $this->getSheet();
        $interator = $sheet->getRowIterator();
        $interator->rewind();
        $headerRow = $interator->current();

        if(empty($headerRow) || !$this->hasHeaderRow) {
            $this->withoutHeaderRow();
            return null;
        }

        return $headerRow->toArray();

    }

    public function getRows(): ?array {

        $sheet = $this->getSheet();
        $interator = $sheet->getRowIterator();
        $interator->rewind();

        if($this->hasHeaderRow){
            $interator->next();
        }

        while ($interator->valid()) {
            $row[] = $this->processRow($interator->current());
            $interator->next();
        }

        return $row ?? null;

    }

    protected function bootReader() {
        $this->reader = new ($this->readerType->value)($this->options);
    }

    protected function getSheet(): SheetInterface {

        $this->bootReader();

        $this->reader->open($this->file);

        return empty($this->sheetName) ?
            $this->getSheetByNumber() :
            $this->getSheetByName();

    }

    protected function getSheetByName(): SheetInterface {

        $sheet = null;

        foreach ($this->reader->getSheetIterator() as $key => $sheet) {
            if ($this->sheetName != "" && $this->sheetName === $sheet->getName()) {
                break;
            }
        }

        if ($this->sheetName != "" && $this->sheetName !== $sheet->getName()) {
            throw new InvalidArgumentException("Sheet name {$this->sheetName} does not exist in {$this->file}.");
        }

        return $sheet;
    }

    protected function getSheetByNumber(): SheetInterface {

        $key = null;
        $sheet = null;

        foreach ($this->reader->getSheetIterator() as $key => $sheet) {
            if ($key === $this->sheetNumber) {
                break;
            }
        }

        if ($this->sheetNumber !== $key) {
            throw new InvalidArgumentException("Sheet Index {$key} does not exist in {$this->file}.");
        }

        return $sheet;

    }

    protected function processRow(Row $row): array {

        $values = [];
        $headers = $this->getHeaders();

        foreach ($row->getCells() as $key => $cell){

            $value =    $cell instanceof Cell\FormulaCell && $this->parseFormulas ?
                        $cell->getComputedValue() :
                        $cell->getValue();

            $values[($headers[$key] ?? $key)] = $value;

        }

        ksort($values);

        return $values;

    }

}
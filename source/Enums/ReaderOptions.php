<?php

namespace CliqueTI\SimpleSheet\Enums;

enum ReaderOptions: string {

    case CSV = "OpenSpout\Reader\CSV\Options";
    case ODS = "OpenSpout\Reader\ODS\Options";
    case XLSX = "OpenSpout\Reader\XLSX\Options";

}

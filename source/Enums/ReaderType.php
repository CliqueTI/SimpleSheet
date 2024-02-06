<?php

namespace CliqueTI\SimpleSheet\Enums;

enum ReaderType: string {

    case CSV = "OpenSpout\Reader\CSV\Reader";
    case ODS = "OpenSpout\Reader\ODS\Reader";
    case XLSX = "OpenSpout\Reader\XLSX\Reader";

}

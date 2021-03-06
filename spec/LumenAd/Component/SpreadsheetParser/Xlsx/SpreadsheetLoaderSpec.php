<?php

namespace spec\LumenAd\Component\SpreadsheetParser\Xlsx;

use PhpSpec\ObjectBehavior;
use LumenAd\Component\SpreadsheetParser\Xlsx\Archive;
use LumenAd\Component\SpreadsheetParser\Xlsx\ArchiveLoader;
use LumenAd\Component\SpreadsheetParser\Xlsx\RelationshipsLoader;
use LumenAd\Component\SpreadsheetParser\Xlsx\RowIteratorFactory;
use LumenAd\Component\SpreadsheetParser\Xlsx\SharedStringsLoader;
use LumenAd\Component\SpreadsheetParser\Xlsx\StylesLoader;
use LumenAd\Component\SpreadsheetParser\Xlsx\ValueTransformerFactory;
use LumenAd\Component\SpreadsheetParser\Xlsx\WorksheetListReader;

class SpreadsheetLoaderSpec extends ObjectBehavior
{
    public function let(
        RelationshipsLoader $relationshipsLoader,
        SharedStringsLoader $sharedStringsLoader,
        StylesLoader $stylesLoader,
        WorksheetListReader $worksheetListReader,
        ValueTransformerFactory $valueTransformerFactory,
        RowIteratorFactory $rowIteratorFactory,
        ArchiveLoader $archiveLoader
    ) {
        $this->beConstructedWith(
            $archiveLoader,
            $relationshipsLoader,
            $sharedStringsLoader,
            $stylesLoader,
            $worksheetListReader,
            $valueTransformerFactory,
            $rowIteratorFactory,
            'spec\LumenAd\Component\SpreadsheetParser\Xlsx\StubSpreadsheet'
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LumenAd\Component\SpreadsheetParser\Xlsx\SpreadsheetLoader');
    }

    public function it_creates_spreadsheet_objects(
        RelationshipsLoader $relationshipsLoader,
        SharedStringsLoader $sharedStringsLoader,
        StylesLoader $stylesLoader,
        WorksheetListReader $worksheetListReader,
        ValueTransformerFactory $valueTransformerFactory,
        RowIteratorFactory $rowIteratorFactory,
        ArchiveLoader $archiveLoader,
        Archive $archive
    ) {
        $archiveLoader->open('path')->willReturn($archive);

        $spreadsheet = $this->open('path');
        $spreadsheet->getArchive()->shouldReturn($archive);
        $spreadsheet->getSharedStringsLoader()->shouldReturn($sharedStringsLoader);
        $spreadsheet->getStylesLoader()->shouldReturn($stylesLoader);
        $spreadsheet->getRowIteratorFactory()->shouldReturn($rowIteratorFactory);
        $spreadsheet->getWorksheetListReader()->shouldReturn($worksheetListReader);
        $spreadsheet->getValueTransformerFactory()->shouldReturn($valueTransformerFactory);
        $spreadsheet->getRelationshipsLoader()->shouldReturn($relationshipsLoader);
    }

    public function it_caches_spreadsheet_objects(
        ArchiveLoader $archiveLoader,
        Archive $archive
    ) {
        $archiveLoader->open('path')->shouldBeCalledTimes(1)->willReturn($archive);

        $spreadsheet = $this->open('path');
        $spreadsheet->getArchive()->shouldReturn($archive);
    }
}

class StubSpreadsheet
{
    protected $sharedStringsLoader;
    protected $worksheetListReader;
    protected $relationshipsLoader;
    protected $stylesLoader;
    protected $rowIteratorFactory;
    protected $valueTransformerFactory;
    protected $archive;

    public function __construct(
        Archive $archive,
        RelationshipsLoader $relationshipsLoader,
        SharedStringsLoader $sharedStringsLoader,
        StylesLoader $stylesLoader,
        WorksheetListReader $worksheetListReader,
        ValueTransformerFactory $valueTransformerFactory,
        RowIteratorFactory $rowIteratorFactory
    ) {
        $this->archive = $archive;
        $this->sharedStringsLoader = $sharedStringsLoader;
        $this->relationshipsLoader = $relationshipsLoader;
        $this->stylesLoader = $stylesLoader;
        $this->worksheetListReader = $worksheetListReader;
        $this->valueTransformerFactory = $valueTransformerFactory;
        $this->rowIteratorFactory = $rowIteratorFactory;
    }

    public function getSharedStringsLoader()
    {
        return $this->sharedStringsLoader;
    }

    public function getRowIteratorFactory()
    {
        return $this->rowIteratorFactory;
    }

    public function getArchive()
    {
        return $this->archive;
    }

    public function getWorksheetListReader()
    {
        return $this->worksheetListReader;
    }

    public function getRelationshipsLoader()
    {
        return $this->relationshipsLoader;
    }

    public function getValueTransformerFactory()
    {
        return $this->valueTransformerFactory;
    }

    public function getStylesLoader()
    {
        return $this->stylesLoader;
    }
}

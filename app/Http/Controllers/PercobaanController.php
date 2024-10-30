<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\Element\Cell;
use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Element\Row;

class PercobaanController extends Controller
{
    public array $questions = [];

    public function extractTables(string $path = "")
    {
        $phpWord = IOFactory::load($path);

        foreach ($phpWord->getSections() as $section) {
            $this->section($section);
        }

        return $this->questions;
    }

    public function section(Section $section)
    {
        foreach($section->getElements() as $element)
        {
            if($element instanceof Table) $this->perTable($element);
        }
    }

    public function perTable(Table $table)
    {
        $rows = $table->getRows();

        $current = new Soal();
        
        //SOAL
        $soal = array_shift($rows);
        $soal = $this->rowExtract($soal);
        $current->id = (int) preg_replace("/[^0-9]/", '', $soal[0]);
        $current->content = $soal[1];

        //JAWABAN
        foreach($rows as $indexJawaban => $row)
        {
            $currentJawaban = $this->rowExtract($row);
            $current->options[] = $currentJawaban[1];

            if(strpos($currentJawaban[0], '*'))
            {
                $current->correct = $indexJawaban;
            }

            // $current = $this->rowExtract($row);
            // $tableContent[$current[0]] = $current[1];
        }

        if($current->correct === 9999) throw new \Exception("Soal nomor $current->id tidak memiliki jawaban");

        $this->questions[] = $current;

        // $soal = array_shift($tableContent);
        
        // dd($soal, $tableContent);
        
    }

    public function rowExtract(Row $row)
    {
        $index = $this->cellExtract($row->getCells()[0]);
        $content = $this->cellExtract($row->getCells()[1]);

        return [
            $index[0], "<p>" . implode("</p><p>", $content) . "</p>"
        ];
    }

    public function cellExtract(Cell $cell)
    {
        $current = [];
        
        foreach($cell->getElements() as $element)
        {
            if($element instanceof TextRun) $current[] = $this->extractTextRun($element);
        }

        return $current;
    }

    public function extractTextRun(TextRun $textRun)
    {
        $current = "";
        foreach($textRun->getElements() as $element)
        {
            if($element instanceof Text) $current .= $this->textToHtml($element);

            else if($element instanceof Image) $current .= $this->imageToHtml($element);
        }

        return $current;
    }

    public function textToHtml(Text $text)
    {
        $current = $text->getText();
        
        if($text->getFontStyle()->isBold()) $current = "<b>$current</b>";
        if($text->getFontStyle()->isItalic()) $current = "<i>$current</i>";

        return $current;
    }

    public function imageToHtml(Image $image)
    {
        $fileName = Str::random() . ".png";
        Storage::disk('public')->put($fileName, base64_decode($image->getImageStringData(true)));
        return "<img src='".Storage::disk('public')->url($fileName)."'/>";
    }
}

class Soal
{
    public function __construct(
        public int $id = 0,
        public string $content = "",
        public array $options = [],
        public int $correct = 9999
    )
    {
            
    }
}
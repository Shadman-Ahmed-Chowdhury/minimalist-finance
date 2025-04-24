<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExpenseExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnWidths, WithDefaultStyles
{
    use Exportable;

    public function __construct(
        protected $categoryId,
        protected $accountId,
        protected $search,
        protected $fromDate,
        protected $toDate,
    ) {}

    public function query()
    {
        return Transaction::with(['fromAccount', 'category'])
            ->expense()
            ->where("user_id", auth()->user()->id)
            ->latest()
            ->when($this->categoryId, function ($query) {
                return $query->where("category_id", $this->categoryId);
            })->when($this->accountId, function ($query) {
                return $query->where("to_account_id", $this->accountId);
            })->when($this->search, function ($query) {
                return $query->where(function ($query) {
                    return $query->where("note", "like", "%" . $this->search . "%")->orWhere('amount', 'like', '%' . $this->search . '%');
                });
            })->when($this->fromDate, function ($query) {
                return $query->where("date", ">=", $this->fromDate);
            })->when($this->toDate, function ($query) {
                return $query->where("date", "<=", $this->toDate);
            });
    }


    public function headings(): array
    {
        return [
            [
                "#",
                "Date",
                "Amount",
                "Type",
                "Account",
                "Category",
                "Note"
            ]
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->date->format("d M Y"),
            $row->amount,
            strtoupper($row->type),
            $row->fromAccount?->name,
            $row->category?->name,
            $row->note
        ];
    }



    public function styles(Worksheet $sheet)
    {

        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => Color::COLOR_CYAN,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
        ]);


        $sheet->getStyle('G1:G' . $sheet->getHighestRow())->getAlignment()->setWrapText(true);


        $sheet->getStyle('G1:G' . $sheet->getHighestRow())->getAlignment()->setWrapText(true);
    }

    public function columnWidths(): array
    {
        return [
            'G' => 55
        ];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        $defaultStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $defaultStyle->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    }
}

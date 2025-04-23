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


class LoanExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnWidths, WithDefaultStyles
{
    use Exportable;

    public function __construct(
        protected $type,
        protected $accountId,
        protected $search,
        protected $fromDate,
        protected $toDate,
    ) {}

    public function query()
    {
        return Transaction::with(['toAccount', 'loanParty'])
            ->loan()
            ->where("user_id", auth()->user()->id)
            ->latest()
            ->when($this->type, function ($query) {
                return $query->where("type", $this->type);
            })->when($this->accountId, function ($query) {
                return $query->where("to_account_id", $this->accountId);
            })->when(
                $this->search,
                fn($query) => $query->where(function ($q) {
                    $q->whereHas('loanParty', fn($q2) => $q2->where('name', 'like', '%' . $this->search . '%'))
                        ->orWhere('amount', 'like', '%' . $this->search . '%')
                        ->orWhere('note', 'like', '%' . $this->search . '%');
                }),
            )
            ->when($this->fromDate, fn($query) => $query->whereHas('loanParty', fn($q) => $q->whereDate('due_date', '>=', $this->fromDate)))
            ->when($this->toDate, fn($query) => $query->whereHas('loanParty', fn($q) => $q->whereDate('due_date', '>=', $this->toDate)))
        ;
    }


    public function headings(): array
    {
        return [
            [
                "#",
                "Date",
                "Name",
                "Email",
                "Type",
                "Amount",
                "Account",
                "Due Date"
            ]
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->date->format("d M Y"),
            $row->loanParty?->name,
            $row->loanParty?->email,
            strtoupper($row->type),
            $row->amount,
            $row->toAccount?->name,
            $row->loanParty?->due_date->format("d M Y")
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
    }

    public function columnWidths(): array
    {
        return [];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        $defaultStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $defaultStyle->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    }
}

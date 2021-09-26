<?php

namespace App\Traits;

trait DataTableHtmlBuilder
{
    public function html()
    {
        return $this->builder()
            ->responsive(true)
            ->scrollX(true)
            ->scrollY('500px')
            ->scrollCollapse(true)
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('lBfrtip')
            ->lengthMenu([
                [10, 25, 50, 75, 100, -1],
                [10, 25, 50, 75, 100, "All"],
            ])
            ->buttons([
                'colvis', 'excelHtml5', 'print', 'pdfHtml5',
            ])
            ->addTableClass('display is-hoverable is-size-7 nowrap')
            ->preDrawCallback("
                function(settings){
                    changeDtButton();
                    $('table').css('display', 'table');
                    removeDtSearchLabel();
                }
            ")
            ->language([
                'processing' => '<i class="fas fa-spinner fa-spin text-green is-size-3"></i>',
            ])
            ->orderBy(1, 'asc');
    }
}

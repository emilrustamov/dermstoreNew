<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Section;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Filter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Возвращаем коллекцию товаров
     */
    public function collection()
    {
        return Product::all();
    }

    /**
     * Заголовки для Excel-файла
     */
    public function headings(): array
    {
        return [
            'Название',
            'Описание',
            'Разделы',
            'Категории',
            'Подкатегории',
            'Бренды',
            'Фильтры (название: значение)',
        ];
    }

    /**
     * Форматируем данные для каждой строки
     */
    public function map($product): array
    {
        return [
            $product->name,
            $product->description,
            $this->getNames(Section::class, $product->sections),
            $this->getNames(Category::class, $product->categories),
            $this->getNames(Subcategory::class, $product->subcategories),
            $this->getNames(Brand::class, $product->brands),
            $this->formatFilters($product->filters),
        ];
    }

    /**
     * Получить названия из модели по массиву ID
     */
    private function getNames($model, $ids): string
    {
        if (is_array($ids)) {
            return implode('; ', $model::whereIn('id', $ids)->pluck('name')->toArray());
        }
        return '';
    }

    /**
     * Форматирование фильтров (название фильтра: значение)
     */
    private function formatFilters($filters): string
    {
        // Если фильтры не являются массивом или пустой строкой, вернем пустую строку
        if (empty($filters) || !is_string($filters)) {
            return '';
        }

        // Попробуем декодировать фильтры из строки
        $decodedFilters = json_decode($filters, true);

        // Если декодирование прошло неудачно (например, нет фильтров), возвращаем пустую строку
        if (!is_array($decodedFilters)) {
            return '';
        }

        $formattedFilters = [];
        foreach ($decodedFilters as $filterId => $filterValues) {
            // Получаем название фильтра по его ID
            $filterName = Filter::where('id', $filterId)->value('name');
            if ($filterName && is_array($filterValues)) {
                // Преобразуем значения фильтра в строку
                $formattedFilters[] = $filterName . ': ' . implode(', ', $filterValues);
            }
        }
        // Возвращаем все фильтры в виде строки, разделенной точкой с запятой
        return implode('; ', $formattedFilters);
    }
}

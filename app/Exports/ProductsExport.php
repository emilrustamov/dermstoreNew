<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Section;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Filter;
use App\Models\Characteristic; // Добавлено
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
            'Характеристики (название: значение)', // Добавлено
            'Диапазон бренда', // Добавлено
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
            $this->formatFilters($product->filters), // Handle filters as array
            $this->formatCharacteristics($product->characteristics), // Добавлено
            $this->getBrandRange($product->ranges), // Handle ranges as array
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
        if (empty($filters) || !is_array($filters)) {
            return '';
        }

        $formattedFilters = [];
        foreach ($filters as $filterId => $filterValues) {
            $filterName = Filter::where('id', $filterId)->value('name');
            if ($filterName && is_array($filterValues)) {
                $formattedFilters[] = $filterName . ': ' . implode(', ', $filterValues);
            }
        }
        return implode('; ', $formattedFilters);
    }

    /**
     * Форматирование характеристик (название характеристики: значение)
     */
    private function formatCharacteristics($characteristics): string
    {
        if (empty($characteristics) || !is_array($characteristics)) {
            return '';
        }

        $formattedCharacteristics = [];
        foreach ($characteristics as $characteristicId => $characteristicValue) {
            $characteristicName = Characteristic::where('id', $characteristicId)->value('name');
            if ($characteristicName) {
                $formattedCharacteristics[] = $characteristicName . ': ' . $characteristicValue;
            }
        }
        return implode('; ', $formattedCharacteristics);
    }

    /**
     * Получить диапазон бренда
     */
    private function getBrandRange($ranges): string
    {
        if (empty($ranges) || !is_array($ranges)) {
            return '';
        }

        return implode(', ', $ranges);
    }
}

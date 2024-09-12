<?php

namespace Drupal\datetime_more\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\datetime\Plugin\Field\FieldWidget\DateTimeDatelistWidget;

/**
 * Plugin implementation of the 'datetime_more_datelist' widget.
 *
 * @FieldWidget(
 *   id = "datetime_more_datelist",
 *   label = @Translation("Select list Datelist more"),
 *   field_types = {
 *     "datetime"
 *   }
 * )
 */
class DateTimeMoreDatelistWidget extends DateTimeDatelistWidget {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'increment' => '1',
      'date_order' => 'YMD',
      'time_type' => '24',
      'year_min' => '1',
      'year_max' => '2999',
      'input_year' => 'number',
      'input_month' => 'select',
      'input_day' => 'select',
      'input_hour' => 'select',
      'input_minute' => 'select',
      'input_second' => 'select',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);

    $date_order = $this->getSetting('date_order');

    if ($this->getFieldSetting('datetime_type') == 'datetime') {
      $time_type = $this->getSetting('time_type');
      $increment = $this->getSetting('increment');
      $year_min = $this->getSetting('year_min');
      $year_max = $this->getSetting('year_max');
    }
    else {
      $time_type = '';
      $increment = '';
    }

    // Set up the date part order array.
    switch ($date_order) {
      case 'YMD':
        $date_part_order = ['year', 'month', 'day'];
        break;

      case 'MDY':
        $date_part_order = ['month', 'day', 'year'];
        break;

      case 'DMY':
        $date_part_order = ['day', 'month', 'year'];
        break;
    }
    switch ($time_type) {
      case '24':
        $date_part_order = array_merge($date_part_order, ['hour', 'minute', 'second']);
        break;

      case 'none':
        break;
    }
    $date_text_parts = [];
    if ($this->getSetting('input_year') == 'number') {
      $date_text_parts[] = 'year';
    }
    if ($this->getSetting('input_month') == 'number') {
      $date_text_parts[] = 'month';
    }
    if ($this->getSetting('input_day') == 'number') {
      $date_text_parts[] = 'day';
    }
    if ($this->getSetting('input_hour') == 'number') {
      $date_text_parts[] = 'hour';
    }
    if ($this->getSetting('input_minute') == 'number') {
      $date_text_parts[] = 'minute';
    }
    if ($this->getSetting('input_second') == 'number') {
      $date_text_parts[] = 'second';
    }

    $element['value'] = [
      '#type' => 'datelist_more',
      '#date_increment' => $increment,
      '#date_part_order' => $date_part_order,
      '#date_year_range' => str_pad($year_min, 4, '0', STR_PAD_LEFT) . ":" . str_pad($year_max, 4, '0', STR_PAD_LEFT),
      '#date_text_parts' => $date_text_parts,
      '#time_type' => $time_type,
    ] + $element['value'];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);

    if ($this->getFieldSetting('datetime_type') == 'datetime') {
      $element['year_min'] = [
        '#type' => 'number',
        '#title' => $this->t('Year minimum'),
        '#default_value' => $this->getSetting('year_min') ?? 1,
      ];
      $element['year_max'] = [
        '#type' => 'number',
        '#title' => $this->t('Year maximum'),
        '#default_value' => $this->getSetting('year_max') ?? 2999,
      ];
      $element['input_year'] = [
        '#type' => 'radios',
        '#title' => $this->t('Input type year'),
        '#default_value' => $this->getSetting('input_year') ?? 'number',
        '#options' => [
          'select' => $this->t('Select'),
          'number' => $this->t('Number'),
        ],
      ];
      $element['input_month'] = [
        '#type' => 'radios',
        '#title' => $this->t('Input type month'),
        '#default_value' => $this->getSetting('input_month') ?? 'select',
        '#options' => [
          'select' => $this->t('Select'),
          'number' => $this->t('Number'),
        ],
      ];
      $element['input_day'] = [
        '#type' => 'radios',
        '#title' => $this->t('Input type day'),
        '#default_value' => $this->getSetting('input_day') ?? 'select',
        '#options' => [
          'select' => $this->t('Select'),
          'number' => $this->t('Number'),
        ],
      ];
      $element['input_hour'] = [
        '#type' => 'radios',
        '#title' => $this->t('Input type hour'),
        '#default_value' => $this->getSetting('input_hour') ?? 'select',
        '#options' => [
          'select' => $this->t('Select'),
          'number' => $this->t('Number'),
        ],
      ];
      $element['input_minute'] = [
        '#type' => 'radios',
        '#title' => $this->t('Input type minute'),
        '#default_value' => $this->getSetting('input_minute') ?? 'select',
        '#options' => [
          'select' => $this->t('Select'),
          'number' => $this->t('Number'),
        ],
      ];
      $element['input_second'] = [
        '#type' => 'radios',
        '#title' => $this->t('Input type second'),
        '#default_value' => $this->getSetting('input_second') ?? 'select',
        '#options' => [
          'select' => $this->t('Select'),
          'number' => $this->t('Number'),
        ],
      ];
      $element['increment'] = [
        '#type' => 'hidden',
        '#value' => 1,
      ];
      $element['time_type'] = [
        '#type' => 'hidden',
        '#value' => 24,
      ];
    }
    else {
      $element['time_type'] = [
        '#type' => 'hidden',
        '#value' => 'none',
      ];

      $element['increment'] = [
        '#type' => 'hidden',
        '#value' => $this->getSetting('increment'),
      ];
    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $summary[] = $this->t('Date part order: @order', ['@order' => $this->getSetting('date_order')]);
    if ($this->getFieldSetting('datetime_type') == 'datetime') {
      $summary[] = $this->t('Year minimum: @year_min', ['@year_min' => $this->getSetting('year_min')]);
      $summary[] = $this->t('Year maximum: @year_max', ['@year_max' => $this->getSetting('year_max')]);
      $summary[] = $this->t('Input type year: @input_year', ['@input_year' => $this->getSetting('input_year')]);
      $summary[] = $this->t('Input type month: @input_month', ['@input_month' => $this->getSetting('input_month')]);
      $summary[] = $this->t('Input type day: @input_day', ['@input_day' => $this->getSetting('input_day')]);
      $summary[] = $this->t('Input type hour: @input_hour', ['@input_hour' => $this->getSetting('input_hour')]);
      $summary[] = $this->t('Input type minute: @input_minute', ['@input_minute' => $this->getSetting('input_minute')]);
      $summary[] = $this->t('Input type second: @input_second', ['@input_second' => $this->getSetting('input_second')]);
    }

    return $summary;
  }

}

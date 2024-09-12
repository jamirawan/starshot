<?php

namespace Drupal\datetime_more\Element;

use Drupal\Core\Datetime\Element\Datelist;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a datelist_more element.
 *
 * @FormElement("datelist_more")
 */
class DatelistMore extends Datelist {

  /**
   * Expands a date element into an array of individual elements.
   *
   * See datetime.
   */
  public static function processDatelist(&$element, FormStateInterface $form_state, &$complete_form) {

    $text_parts = !empty($element['#date_text_parts']) ? $element['#date_text_parts'] : [];
    $element = parent::processDatelist($element, $form_state, $complete_form);

    if (in_array('year', $text_parts)) {
      $element['year']['#type'] = 'number';
      $element['year']['#size'] = 4;
      $element['year']['#maxlength'] = 4;
      $range = static::datetimeRangeYears($element['#date_year_range']);
      $element['year']['#min'] = $range[0];
      $element['year']['#max'] = $range[1];
    }
    if (in_array('month', $text_parts)) {
      $element['month']['#type'] = 'number';
      $element['month']['#size'] = 2;
      $element['month']['#maxlength'] = 2;
      $element['month']['#min'] = 1;
      $element['month']['#max'] = 12;
    }
    if (in_array('day', $text_parts)) {
      $element['day']['#type'] = 'number';
      $element['day']['#size'] = 2;
      $element['day']['#maxlength'] = 2;
      $element['day']['#min'] = 1;
      $element['day']['#max'] = 31;
    }
    if (in_array('hour', $text_parts)) {
      $element['hour']['#type'] = 'number';
      $element['hour']['#size'] = 2;
      $element['hour']['#maxlength'] = 2;
      $element['hour']['#min'] = 0;
      $element['hour']['#max'] = 23;
    }
    if (in_array('minute', $text_parts)) {
      $element['minute']['#type'] = 'number';
      $element['minute']['#size'] = 2;
      $element['minute']['#maxlength'] = 2;
      $element['minute']['#min'] = 0;
      $element['minute']['#max'] = 59;
    }
    if (in_array('second', $text_parts)) {
      $element['second']['#type'] = 'number';
      $element['second']['#size'] = 2;
      $element['second']['#maxlength'] = 2;
      $element['second']['#min'] = 0;
      $element['second']['#max'] = 59;
    }

    return $element;
  }

}

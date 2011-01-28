<?php
/*
 * form - label for an input
 */
function FormLabel($id, $value) {
    $output = sprintf('<label for="%s">%s</label> :', $id, $value);
    return $output;
}


/*
 * form - reset button
 */
function FormReset() {
    $output = '<input type="reset" name="reset" value="Reset" onclick="return confirm(\'Are you sure you want to clear all fields?\');" />';
    return $output;
}


/*
 * form - submit button
 */
function FormSubmit($id, $value, $form) {
    $output = sprintf('<input type="submit" id="%s" name="%s" value="%s" />', $id, $id, $value);

    $jscriptoutput = sprintf('var fv%s=new FormValidator($(\'%s\'),{onElementPass:ValidateElementPass,onElementFail:ValidateElementFail,onFormValidate:ValidateForm});', $form, $form);
    $output .= JsBlock($jscriptoutput, true);

    return $output;
}


/*
 * form - hidden field
 */
function FormHidden($id, $value) {
    $output = sprintf('<input type="hidden" id="%s" name="%s" value="%s" />', $id, $id, $value);
    return $output;
}


/*
 * form - text box
 */
function FormText($id, $value, $class, $maxlength) {
    $output = sprintf('<input type="text" id="%s" name="%s" value="%s" %s%s%s />',
        $id,
        $id,
        $value,
        intval($maxlength) > 0 ? sprintf(' maxlength="%s"', intval($maxlength)) :	'',
        intval($maxlength) < 30 ? sprintf(' size="%s"', ceil(intval($maxlength) * 1.5)) : ' size="40"',
        $class != '' ? sprintf(' class="%s"', $class) : ''
    );
    $output .= sprintf(' <span id="validadvice%s"></span>', $id);

    return $output;
}


/*
 * form - password box
 */
function FormPassword($id, $value, $class, $maxlength) {
    $output = sprintf('<input type="password" id="%s" name="%s" value="%s" %s%s%s />',
        $id,
        $id,
        $value,
        intval($maxlength) > 0 ? sprintf(' maxlength="%s"', intval($maxlength)) : '',
        intval($maxlength) < 30 ? sprintf(' size="%s"', ceil(intval($maxlength) * 1.25)) : '',
        $class != '' ? sprintf(' class="%s"', $class) : ''
    );
    $output .= sprintf(' <span id="validadvice%s"></span>', $id);

    return $output;
}


/*
 * form - date picker
 */
function FormDate($id, $value, $class) {
    $output = FormText($id, $value, $class, 10);
    $output .= JsBlock(JsCalendar($id), true);

    return $output;
}


/*
 * form - check box
 */
function FormCheck($id, $key, $value, $class) {
    $output = sprintf('<input type="checkbox" name="%s" id="%s" value="t" %s %s />',
        $id,
        $id,
        $class != '' ? sprintf(' class="%s"', $class) : '',
        $key ? ' checked="checked"' : ''
    );

    if ($value != '') {
        $output .= ' <label for="'.$id.'">'.$value.'</label>';
    }

    $output .= sprintf(' <span id="validadvice%s"></span>', $id);

    return $output;
}


/*
 * form - radio buttons
 */
function FormRadio($id, $values, $selected, $class, $break = false) {
    $output = '';
    $first = true;

    foreach ($values as $key=>$value) {
        if ($first) {
            $first = false;
        } elseif ($break) {
            $output .= '<br />';
        }

        $output .= sprintf('<input type="radio" name="%s" id="%s" value="%s" %s %s /> <label for="%s">%s</label>',
            $id,
            $id.'-'.$key,
            $key,
            $class != '' ? 'class="' . $class . '"' : '',
            $selected==$key ? 'checked="checked"' : '',
            $id.'-'.$key,
            $value
        );
    }

    $output .= sprintf(' <span id="validadvice%s"></span>', $id);

    return $output;
}


/*
 * form - text area
 */
function FormTextArea($id, $value, $class, $rows = 0) {
    $output = sprintf('<textarea id="%s" name="%s" cols="60" %s %s>%s</textarea>',
        $id,
        $id,
        $rows != 0 ? 'rows="' . $rows . '"' : '',
        $class != '' ? 'class="' . $class . '"' : '',
        $value
    );
    $output .= sprintf(' <span id="validadvice%s"></span>', $id);

    return $output;
}


/*
 * form - combo box
 *
 * values - an associative array of value<>key pairs
 */
function FormCombo($id, $values, $selected = '', $class = '') {
    $output = '<select id="'.$id.'" name="'.$id.'"';
    if ($class!='') {
        $output .= ' class="'.$class.'"';
    }
    $output .= '>';

    foreach ($values as $key=>$value) {
        $output .= '<option value="'.$key.'"';
        if ($selected==$key) {
            $output .= ' selected="selected"';
        }
        $output .= '>'.$value.'</option>';
    }
    $output .= '</select>';
    $output .= sprintf(' <span id="validadvice%s"></span>', $id);

    return $output;
}


/*
 * form - combo box dynamically linked to another
 *
 * values - an array of value<>key<>linkeditem pairs
 */
function FormLinkedCombo($id, $linkedname, $values, $selected = '', $class = '') {
    $output = FormCombo($id, array(), $selected, $class);

    $joutput = 'var linkedcombo_'.$id.' = new Array();';
    $hi = null;
    $count = 0;
    foreach ($values as $data) {
        if ($data['l']!=$hi) {
            $hi = $data['l'];
            $count = 0;
            $joutput .= 'linkedcombo_'.$id.'['.$hi.'] = new Array();';
        }
        $joutput .= 'linkedcombo_'.$id.'['.$hi.']['.$count.'] = "'.$data['v'].'";';
        $count++;
        $joutput .= 'linkedcombo_'.$id.'['.$hi.']['.$count.'] = "'.$data['k'].'";';
        $count++;
    }

    $joutput .= '$("'.$linkedname.'").addEvents({';
    $joutput .= 'change: function(){';
    $joutput .= 'PopulateCombo("'.$linkedname.'","'.$id.'",linkedcombo_'.$id.');';
    $joutput .= '}';
    $joutput .= '});';
    $joutput .= 'PopulateCombo("'.$linkedname.'","'.$id.'",linkedcombo_'.$id.');';
    $joutput .= 'SetComboItem("'.$id.'","'.$selected.'");';
    $output .= JsBlock($joutput, true);

    return $output;
}
?>
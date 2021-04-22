<?php
/**
 * Include REDCap header.
 */
require_once APP_PATH_DOCROOT . "ProjectGeneral/header.php"; 



/**
 * Initialize Custom Report Builder object, and call method
 * to generate Index page.
 */
//$customReportBuilder = new \BCCHR\CustomTemplateEngine\CustomTemplateEngine();
//$customReportBuilder->generateIndexPage();
?>

<h4>Fx Slider Configuration</h4>
<h5>Enable Fx Slider for a field</h5>
In the designer:
<ul>
    <li>Set the field type to Text Box</li>
    <li>Set the custom alignment to Left / Vertical (LV)</li>
    <li>In the field label set the label as usual, but add a div HTML element like this:<br>
        <code>&lt;div class=&quot;sliderWrapper&quot; id=&quot;field_variable_name&quot;&gt;&lt;/div&gt;</code><br>
        replacing field_variable_name with the actual variable name of the field in question.<br>
        For example for a filed having a variable name of help_fish, you would use:<br>
        <code>How do you feel this helped?<br>
        &lt;div class=&quot;sliderWrapper&quot; id=&quot;help_fish&quot;&gt;&lt;/div&gt;</code>
    </li>
</ul>

<h5>Changing the anchor labels</h5>
<ul>
    <li>By default the slider anchors will be: Not at all , Somewhat , A lot</li>
    <li>To change them, add a hidden HTML element like this:<br>
        <code>&lt;input type=&quot;hidden&quot; id=&quot;field_variable_name-anchors&quot; value=&quot;Low anchor|Middle anchor|High anchor&quot;&gt;</code><br>
        replacing field_variable_name with the actual variable name of the field in question.<br>
        Adding to the previous example for a variable name of help_fish, you would use:<br>
        <code>How do you feel this helped?<br>
        &lt;div class=&quot;sliderWrapper&quot; id=&quot;help_fish&quot;&gt;&lt;/div&gt;&lt;input type=&quot;hidden&quot; id=&quot;help_fish-anchors&quot; value=&quot;Very little|Some|Very much&quot;&gt;</code>
    </li>
</ul>

<h5>Other settings</h5>
If you go to to External Modules in the Applications left menu and click on Configure Fx Slider Module, you can change
the following settings:
<ul>
    <li>Show the slider's no answer message:<br>
        When checked the no answer message is visible when the slider is in the no answer zone.
    </li>
    <li>Slider's no answer message:<br>
        Message displayed when when the slider is in the no answer zone.<br>
        Defaults to "Move the slider out of the left box to set a response".
    </li>
    <li>Slider's answer message:<br>
        Message displayed when when the slider is in the answer zone.<br>
        Defaults to "".
    </li>
    <li>Slider's default anchors:<br>
        Anchors displayed by default when when not specified for a field.<br>
        Defaults to "Not at all|Somewhat|A lot".
    </li>
    <li>Url for custom css file (e.g., to customize handle and background): (Admin only)<br>
        This would be the URL of a css file on the server which would reference custom graphics, colors... to change the
        slider's aspect.
    </li>
    <li>Show the underlying textbox for testing:<br>
        This shows the text box whose value REDCap actually saves (by default the position of the slider handle).<br>
        This allows you to verify that the correct value changes as you move the handle when you have multiple sliders
        on a page.
    </li>
    <li>Show the scores rather than the position of the slider in the textbox: (Admin only.<span style="color:red;"> DO NOT USE IN PRODUCTION</span>)<br>
        This displays the score on a 0 to 20 scale (0 being no answer) instead of the slider position.<br>
        This allows to visualize where the scores fall relative to the anchors (for development).<br>
        Be aware that if you save the record when the score is displayed, it will be saved a if it where a position,
        effectively removing the response.<br>
        The score is calculated as:<br>
        <code>if (position < 20) score = 0 else score = Math.ceil((position-20)/(520-20)*20)</code>
    </li>
</ul>

<?php
/**
 * Include REDCap footer.
 */
require_once APP_PATH_DOCROOT . "ProjectGeneral/footer.php";
?>
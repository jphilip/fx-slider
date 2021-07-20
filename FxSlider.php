<?php
namespace CANHR\FxSlider;

use \REDCap as REDCap;
require_once "emLoggerTrait.php";

class FxSlider extends \ExternalModules\AbstractExternalModule
{
  use emLoggerTrait;

  private $pid;
  private $timer_js_file;
  private $range_js_file;
  private $slider_js_file;
  private $slider_css_file;
  private $show_textbox;
  private $show_score;
  private $show_slider_msg;
  private $slider_msg;
  private $slider_msg2;
  private $default_anchors;
  
  
  function __construct()
  {
    parent::__construct();
    $this->pid = $this->getProjectId();
  }
    
  function redcap_survey_page_top($project_id, $record = NULL, $instrument, $event_id, $group_id = NULL,
  $survey_hash = NULL, $response_id = NULL, $repeat_instance = 1)
  {
    
    $this->timer_js_file = $this->getUrl('/assets/timer.js');
    $this->range_js_file = $this->getUrl('/assets/range.js');
    $this->slider_js_file = $this->getUrl('/assets/slider.js');
    $this->slider_css_file = $this->getProjectSetting("css-url");
    if (empty($this->slider_css_file))
      $this->slider_css_file = $this->getUrl('/assets/bluecurve.css');
    $this->show_textbox = (int) $this->getProjectSetting("show-textbox");
    if (empty($this->show_textbox))
      $this->show_textbox = 0;
    $this->show_score = (int) $this->getProjectSetting("show-score");
    if (empty($this->show_score) | !$this->show_textbox)
      $this->show_score = 0;
    $this->show_slider_msg = (int) $this->getProjectSetting("show-slider-msg");
    if (empty($this->show_slider_msg))
      $this->show_slider_msg = 0;
    $this->slider_msg = $this->getProjectSetting("slider-msg");
    if (empty($this->slider_msg))
      $this->slider_msg = "Move the slider out of the left box to set a response";
    $this->slider_msg2 = $this->getProjectSetting("slider-msg2");
    if (empty($this->slider_msg2))
      $this->slider_msg2 = "&nbsp;";
    $this->default_anchors = $this->getProjectSetting("default-anchors");
    if (empty($this->default_anchors))
      $this->default_anchors = "Not at all|Somewhat|A lot";

    $str = <<<"EOT"

<script type="text/javascript" src="{$this->timer_js_file}"></script>
<script type="text/javascript" src="{$this->range_js_file}"></script>
<script type="text/javascript" src="{$this->slider_js_file}"></script>
<link rel="stylesheet" type="text/css" media="all" href="{$this->slider_css_file}">

<script type="text/javascript">
$(document).ready( function(){
  $(".sliderWrapper").each(function(i ,wp) {
    var qId = wp.id; //Name of REDCap variable and control, id of slider wrapper 
    var redcapInput = document.getElementsByName(qId)[0];
    redcapInput.style.width = "40px";
    redcapInput.readOnly = true;
    // redcapInput.style.marginTop = "15px";
    
  ////// If module setting show-textbox is true, show textbox too///////////  
  ///// This was a hack for hook framework needs to be changed to an option for EM /////
    if (!{$this->show_textbox})
      redcapInput.style.display = 'none';
    
    var el = document.getElementById(qId + "-anchors");
    if (el)
      var anchors = el.value.split("|");
    else
      var anchors = "{$this->default_anchors}".split("|");
        
    // Set height of slider wrapper
    //wp.style.height = "50px";
    //if ({$this->show_slider_msg})
    //  wp.style.height = "85px";

    wp.innerHTML = '<table style="width: 650px"><tbody><tr class="ticks"><td style="width:  47px;"></td><td style="width: 129px;">|</td><td style="width: 144px;">|</td><td style="width: 144px;">|</td><td style="width: 144px;">|</td><td>|</td></tr></tbody></table><div id="slider-' + qId + '" class="horizontal dynamic-slider-control slider" style="left: 1px; width: 650px; position: relative; top: 0px; height: 25px;"><input id="slider-input-' + qId + '" class="slider-input" style="width: 465px; position: relative; height: 16px"><div>&nbsp;</div>';
        
    
    var s1 = new Slider(document.getElementById("slider-" + qId), document.getElementById("slider-input-" + qId), 520, 0);

    var anchorTab = document.createElement('table');
    anchorTab.innerHTML = '<tbody><tr class="scaleRow"><td style="width: 106px;">' + anchors[0] + '</td><td style="width: 109px;">&nbsp;</td><td colspan="2">' + anchors[1] + '</td><td style="width: 103px;">&nbsp;</td><td style="width:  94px;text-align:right;padding-right:5px;">' + anchors[2] + '</td></tr><tr><td id="slidermsg-' + qId + '" class="sldrmsg opacity75" colspan="6">{$this->slider_msg}</td></tr></tbody>';
    anchorTab.style = "width: 650px; position: relative; left: 0px; top: 8px;"
    wp.insertAdjacentElement('afterend', anchorTab);
    
    
    s1.onchange = function () {
      fishValue = s1.getValue();
      if (fishValue < 20) {
        fishScore = 0;
        if ($this->show_slider_msg) {         
          $("#slidermsg-" + qId).html("$this->slider_msg");
          $("#slidermsg-" + qId).css('visibility', 'visible');
        } else {
          $("#slidermsg-" + qId).css('visibility', 'hidden');
        }
      } else {
        fishScore = Math.ceil((fishValue-20)/(520-20)*20);
        if ($this->show_slider_msg) {
          $("#slidermsg-" + qId).html("$this->slider_msg2");
          $("#slidermsg-" + qId).css('visibility', 'visible');
        } else {
          $("#slidermsg-" + qId).css('visibility', 'hidden');
        }
      }
      
      ///////Show score to test, but value (slider position) in production)////////
      redcapInput.value = !{$this->show_score} ? fishValue : fishScore;
    };
    
    if (redcapInput.value == '')
      redcapInput.value = 0;
    s1.setValue(redcapInput.value);

    // Hide slider message at first if set up that way
    if (!{$this->show_slider_msg})
      $("#slidermsg-" + qId).hide();

  });
});
</script>
EOT;
    echo $str; 
  }

  function redcap_data_entry_form_top($project_id, $record = NULL, $instrument, $event_id, $group_id = NULL, $repeat_instance = 1)
  {
      $this->redcap_survey_page_top($project_id, $record, $instrument, $event_id, $group_id,
      NULL, NULL, $repeat_instance);
  }
}
<?php

class quiz_comparereport_overview_table extends table_sql {

    var $useridfield = 'userid';

    var $candelete;
    var $reporturl;
    var $displayoptions;
    var $regradedqs = array();

    function quiz_comparereport_overview_table($quiz ,$quiz2, $qmsubselect, $groupstudents,
                $students, $detailedmarks, $questions, $question2s, $candelete, $reporturl, $displayoptions, $context){
        parent::table_sql('mod-quiz-report-overview-report');
	
		$this->set_attribute('style', 'margin-left: auto;margin-right: auto;');
        $this->quiz = $quiz;
        $this->quiz2 = $quiz2;
        $this->qmsubselect = $qmsubselect;
        $this->groupstudents = $groupstudents;
        $this->students = $students;
        $this->detailedmarks = $detailedmarks;
        $this->questions = $questions;
        $this->question2s = $question2s;
        $this->candelete = $candelete;
        $this->reporturl = $reporturl;
        $this->displayoptions = $displayoptions;
        $this->context = $context;
    }
    function build_table(){
        global $CFG, $DB;
        if ($this->rawdata) {
            // Define some things we need later to process raw data from db.
            $this->strtimeformat = str_replace(',', '', get_string('strftimedatetime'));
            $rowno=0;
			$this->total=0;
			$this->total2=0;
			if ($this->rawdata){
				foreach($this->rawdata as $row){
					if($row->quiz!=$this->quiz2->id){
						$rowno++;
						$formattedrow = $this->format_row($row);
						$this->add_data_keyed($formattedrow);
					}
				}
			}
            //end of adding data from attempts data to table / download
            //now add averages at bottom of table :
         
			
            $this->add_separator();
			foreach($this->sum as $s=>$sum){
				$overall[$s]=$sum[1].'/'.$sum[0];
			}
			$overall['firstname']=get_string('overallaverage');
			$overall['total']=$this->total/$rowno;
			$overall['total2']=$this->total2/$rowno;
			$this->add_data_keyed($overall);
            
        }
    }

    function wrap_html_start(){
        if (!$this->is_downloading()) {
            if ($this->candelete) {
                // Start form
                $url = new moodle_url($this->reporturl, $this->displayoptions);
                echo '<div id="tablecontainer" class="overview-tablecontainer">';
                echo '<form id="attemptsform" method="post" action="' . $this->reporturl->out_omit_querystring() .'">';
                echo '<div style="display: none;">';
                echo html_writer::input_hidden_params($url);
                echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey())) . "\n";
                echo '</div>';
                echo '<div>';
            }
        }
    }
    function wrap_html_finish(){
        if (!$this->is_downloading()) {
            // Print "Select all" etc.
            if ($this->candelete) {
                $strreallydel  = addslashes_js(get_string('deleteattemptcheck','quiz'));
                echo '<div id="commands">';
                echo '<a href="javascript:select_all_in(\'DIV\',null,\'tablecontainer\');">'.
                        get_string('selectall', 'quiz').'</a> / ';
                echo '<a href="javascript:deselect_all_in(\'DIV\',null,\'tablecontainer\');">'.
                        get_string('selectnone', 'quiz').'</a> ';
                echo '&nbsp;&nbsp;';
                if (has_capability('mod/quiz:regrade', $this->context)){
                    echo '<input type="submit" name="regrade" value="'.get_string('regradeselected', 'quiz_overview').'"/>';
                }
                echo '<input type="submit" onclick="return confirm(\''.$strreallydel.'\');" name="delete" value="'.get_string('deleteselected', 'quiz_overview').'"/>';
                echo '</div>';
                // Close form
                echo '</div>';
                echo '</form></div>';
            }
        }
    }


    function col_checkbox($attempt){
        if ($attempt->attempt){
            return '<input type="checkbox" name="attemptid[]" value="'.$attempt->attempt.'" />';
        } else {
            return '';
        }
    }

    function col_picture($attempt){
        global $COURSE, $OUTPUT;
        $user = new stdClass();
        $user->id = $attempt->userid;
        $user->lastname = $attempt->lastname;
        $user->firstname = $attempt->firstname;
        $user->imagealt = $attempt->imagealt;
        $user->picture = $attempt->picture;
        $user->email = $attempt->email;
        return $OUTPUT->user_picture($user);
    }

    function col_fullname($attempt){
        $html = parent::col_fullname($attempt);
        if ($this->is_downloading()) {
            return $html;
        }

        return $html . '<br /><a class="reviewlink" href="review.php?q='.$this->quiz->id.'&amp;attempt='.$attempt->attempt.
                '">'.get_string('reviewattempt', 'quiz').'</a>';
    }

    function col_timestart($attempt){
        if ($attempt->attempt) {
            return userdate($attempt->timestart, $this->strtimeformat);
        } else {
            return  '-';
        }
    }
    function col_timefinish($attempt){
        if ($attempt->attempt && $attempt->timefinish) {
            return userdate($attempt->timefinish, $this->strtimeformat);
        } else {
            return  '-';
        }
    }

    function col_duration($attempt){
        if ($attempt->timefinish) {
            return format_time($attempt->timefinish - $attempt->timestart);
        } elseif ($attempt->timestart) {
            return get_string('unfinished', 'quiz');
        } else {
            return '-';
        }
    }

    function col_sumgrades($attempt){
        if (!$attempt->timefinish) {
            return '-';
        }

        $grade = quiz_rescale_grade($attempt->sumgrades, $this->quiz);
        if ($this->is_downloading()) {
            return $grade;
        }

        if (isset($this->regradedqs[$attempt->attemptuniqueid])){
            $newsumgrade = 0;
            $oldsumgrade = 0;
            foreach ($this->questions as $question){
                if (isset($this->regradedqs[$attempt->attemptuniqueid][$question->id])){
                    $newsumgrade += $this->regradedqs[$attempt->attemptuniqueid][$question->id]->newgrade;
                    $oldsumgrade += $this->regradedqs[$attempt->attemptuniqueid][$question->id]->oldgrade;
                } else {
                    $newsumgrade += $this->gradedstatesbyattempt[$attempt->attemptuniqueid][$question->id]->grade;
                    $oldsumgrade += $this->gradedstatesbyattempt[$attempt->attemptuniqueid][$question->id]->grade;
                }
            }
            $newsumgrade = quiz_rescale_grade($newsumgrade, $this->quiz);
            $oldsumgrade = quiz_rescale_grade($oldsumgrade, $this->quiz);
            $grade = "<del>$oldsumgrade</del><br />$newsumgrade";
        }

        $gradehtml = '<a href="review.php?q='.$this->quiz->id.'&amp;attempt='.$attempt->attempt.
                '" title="'.get_string('reviewattempt', 'quiz').'">'.$grade.'</a>';
        if ($this->qmsubselect && $attempt->gradedattempt){
            $gradehtml = '<div class="highlight">'.$gradehtml.'</div>';
        }
        return $gradehtml;
    }

	function format_row($row){
		$formattedrow = array();
        foreach (array_keys($this->columns) as $column){
            $colmethodname = 'col_'.$column;
            if (method_exists($this, $colmethodname)){
                $formattedcolumn = $this->$colmethodname($row);
            } else {
                $formattedcolumn = $this->other_cols($column, $row);
                if ($formattedcolumn===NULL){
                    $formattedcolumn = $this->rawdata[$row->uniqueid]->$column;
                }

            }
            $formattedrow[$column] = $formattedcolumn;
        }
        return $formattedrow;
    }
    /**
     * @param string $colname the name of the column.
     * @param object $attempt the row of data - see the SQL in display() in
     * mod/quiz/report/overview/report.php to see what fields are present,
     * and what they are called.
     * @return string the contents of the cell.
     */
    function other_cols($colname, $attempt){
        global $OUTPUT;
		$uid=$attempt->uniqueid;
		if(!empty($this->rawdata[str_replace('#'.$this->quiz->id.'@','#'.$this->quiz2->id.'@',$uid)])){
			$a=str_replace($this->quiz->id,$this->quiz2->id,$uid);
			$a=split('@',$a);
			$i=1;
			while(!empty($this->rawdata[$a[0].'@'.$i])){
				$attempt1=$this->rawdata[$a[0].'@'.$i];
				$i++;
			}
		}
		
		
		$stateforqinattempt = false;
		if($colname=='total'){	
			return $attempt->total;
		}elseif (preg_match('/^qsgrade([0-9]+)pre$/', $colname, $matches)){
			
            $questionid = $matches[1];
            $question = $this->questions[$questionid];
            if (isset($this->gradedstatesbyattempt[$attempt->attemptuniqueid][$questionid])){
                $stateforqinattempt = $this->gradedstatesbyattempt[$attempt->attemptuniqueid][$questionid];
            } 
            if ($stateforqinattempt && question_state_is_graded($stateforqinattempt)) {
                $grade = quiz_rescale_grade($stateforqinattempt->grade, $this->quiz, 'question');
                if (!$this->is_downloading()) {
                    if (isset($this->regradedqs[$attempt->attemptuniqueid][$questionid])){
                        $gradefromdb = $grade;
                        $newgrade = quiz_rescale_grade($this->regradedqs[$attempt->attemptuniqueid][$questionid]->newgrade, $this->quiz, 'question');
                        $oldgrade = quiz_rescale_grade($this->regradedqs[$attempt->attemptuniqueid][$questionid]->oldgrade, $this->quiz, 'question');

                        $grade = '<del>'.$oldgrade.'</del><br />'.
                                $newgrade;
                    }

                    $link = new moodle_url("/mod/quiz/reviewquestion.php?attempt=$attempt->attempt&question=$question->id");
                    $action = new popup_action('click', $link, 'reviewquestion', array('height' => 450, 'width' => 650));
                    $linktopopup = $OUTPUT->action_link($link, $grade, $action, array('title'=>get_string('reviewresponsetoq', 'quiz', $question->formattedname)));

                    if (($this->questions[$questionid]->maxgrade != 0)){
                        $fractionofgrade = $stateforqinattempt->grade
                                        / $this->questions[$questionid]->maxgrade;
                        $qclass = question_get_feedback_class($fractionofgrade);
                        $feedbackimg = question_get_feedback_image($fractionofgrade);
                        $questionclass = "que";
						 $linktopopup = $OUTPUT->action_link($link, $feedbackimg, $action, array('title'=>get_string('reviewresponsetoq', 'quiz', $question->formattedname)));
						 if(!empty($this->sum[$colname])){
							$this->sum[$colname][0]++;
						}else{	
							$this->sum[$colname][0]=1;
							$this->sum[$colname][1]=0;
							
						}
						if(empty($this->rawdata[$uid]->total)){
							$this->rawdata[$uid]->total=0;
						}
						if($qclass=='correct'){
							$this->sum[$colname][1]++;
							$this->rawdata[$uid]->total=$this->rawdata[$uid]->total+50;
							$this->total+=50;
						}
                        return "<span class=\"$questionclass\"><span class=\"$qclass\">".$linktopopup."</span></span>";
                    } else {
						if(empty($this->sum[$colname])){
							$this->sum[$colname][0]=0;
						}
                        return $linktopopup;
                    }
				} else {
                    return $grade;
                }
            } else if ($stateforqinattempt && question_state_is_closed($stateforqinattempt)) {
                $text = get_string('requiresgrading', 'quiz_overview');
                if (!$this->is_downloading()) {
                    $link = new moodle_url("/mod/quiz/reviewquestion.php?attempt=$attempt->attempt&question=$question->id");
                    $action = new popup_action('click', $link, 'reviewquestion', array('height' => 450, 'width' => 650));
                    return $OUTPUT->action_link($link, $text, $action, array('title'=>get_string('reviewresponsetoq', 'quiz', $question->formattedname)));
                } else {
                    return $text;
                }
            } else {
                return '--';
            }
        } else if (preg_match('/^qsgrade([0-9]+)post$/', $colname, $matches)){
			if(!empty($attempt1)){
				$questionid = $matches[1];
				$question = $this->question2s[$questionid];
				if (isset($this->gradedstatesbyattempt[$attempt1->attemptuniqueid][$questionid])){
					$stateforqinattempt = $this->gradedstatesbyattempt[$attempt1->attemptuniqueid][$questionid];
				} else {
					$stateforqinattempt = false;
				}
				if ($stateforqinattempt && question_state_is_graded($stateforqinattempt)) {
					$grade = quiz_rescale_grade($stateforqinattempt->grade, $this->quiz, 'question');
					if (!$this->is_downloading()) {
						if (isset($this->regradedqs[$attempt1->attemptuniqueid][$questionid])){
							$gradefromdb = $grade;
							$newgrade = quiz_rescale_grade($this->regradedqs[$attempt1->attemptuniqueid][	$questionid]->newgrade, $this->quiz, 'question');
							$oldgrade = quiz_rescale_grade($this->regradedqs[$attempt1->attemptuniqueid][$questionid]->oldgrade, $this->quiz, 'question');

							$grade = '<del>'.$oldgrade.'</del><br />'.$newgrade;
						}

						$link = new moodle_url("/mod/quiz/reviewquestion.php?attempt=$attempt1->attempt&question=$question->id");
						$action = new popup_action('click', $link, 'reviewquestion', array('height' => 450, 'width' => 650));
						$linktopopup = $OUTPUT->action_link($link, $grade, $action, array('title'=>get_string('reviewresponsetoq', 'quiz', $question->formattedname)));
						if (($this->question2s[$questionid]->maxgrade != 0)){
							$fractionofgrade = $stateforqinattempt->grade/ $this->questions[$questionid]->maxgrade;
							$qclass = question_get_feedback_class($fractionofgrade);
							$feedbackimg = question_get_feedback_image($fractionofgrade);
							$questionclass = "que";
							$linktopopup = $OUTPUT->action_link($link, $feedbackimg, $action, array('title'=>get_string('reviewresponsetoq', 'quiz', $question->formattedname)));
							if(!empty($this->sum[$colname])){
								$this->sum[$colname][0]++;
							}else{	
								$this->sum[$colname][0]=1;
								$this->sum[$colname][1]=0;
							}
							if(empty($this->rawdata[$uid]->total2)){
								$this->rawdata[$uid]->total2=0;
							}
							if($qclass=='correct'){
								$this->sum[$colname][1]++;
								$this->rawdata[$uid]->total2=$this->rawdata[$uid]->total2+50;
								$this->total2+=50;
							}
							return "<span class=\"$questionclass\"><span class=\"$qclass\">".$linktopopup."</span></span>";
						} else {
							if(empty($this->rawdata[$uid]->total2)){
								$this->rawdata[$uid]->total2=0;
							}
							if(empty($this->sum[$colname])){
								$this->sum[$colname][0]=0;
								$this->sum[$colname][1]=0;
							}
							return $linktopopup;
						}

					} else {
						return $grade;
					}
				} else if ($stateforqinattempt && question_state_is_closed($stateforqinattempt)) {
					$text = get_string('requiresgrading', 'quiz_overview');
					if (!$this->is_downloading()) {
						$link = new moodle_url("/mod/quiz/reviewquestion.php?attempt=$attempt->attempt&question=$question->id");
						$action = new popup_action('click', $link, 'reviewquestion', array('height' => 450, 'width' => 650));
						return $OUTPUT->action_link($link, $text, $action, array('title'=>get_string('reviewresponsetoq', 'quiz', $question->formattedname)));
					} else {
						return $text;
					}
				}
			}else{
				if(empty($this->rawdata[$uid]->total2)){
					$this->rawdata[$uid]->total2='--';
				}
				return '--';
			}
        }else{
            return NULL;
        }
    }

    function col_feedbacktext($attempt){
        if ($attempt->timefinish) {
            if (!$this->is_downloading()) {
                return quiz_report_feedback_for_grade(quiz_rescale_grade($attempt->sumgrades, $this->quiz, false), $this->quiz->id, $this->context);
            } else {
                return strip_tags(quiz_report_feedback_for_grade(quiz_rescale_grade($attempt->sumgrades, $this->quiz, false), $this->quiz->id, $this->context));
            }
        } else {
            return '-';
        }

    }
    function col_regraded($attempt){
        if ($attempt->regraded == '') {
            return '';
        } else if ($attempt->regraded == 0) {
            return get_string('needed', 'quiz_overview');
        } else if ($attempt->regraded == 1) {
            return get_string('done', 'quiz_overview');
        }
    }
    function query_db($pagesize, $useinitialsbar=true){
        // Add table joins so we can sort by question grade
        // unfortunately can't join all tables necessary to fetch all grades
        // to get the state for one question per attempt row we must join two tables
        // and there is a limit to how many joins you can have in one query. In MySQL it
        // is 61. This means that when having more than 29 questions the query will fail.
        // So we join just the tables needed to sort the attempts.
        if($sort = $this->get_sql_sort()) {
            if ($this->detailedmarks) {
                $this->sql->from .= ' ';
                $sortparts    = explode(',', $sort);
                $matches = array();
                foreach($sortparts as $sortpart) {
                    $sortpart = trim($sortpart);
                    if (preg_match('/^qsgrade([0-9]+)/', $sortpart, $matches)){
                        $qid = intval($matches[1]);
                        $this->sql->fields .=  ", qs$qid.grade AS qsgrade$qid, qs$qid.event AS qsevent$qid, qs$qid.id AS qsid$qid";
                        $this->sql->from .= "LEFT JOIN {question_sessions} qns$qid ON qns$qid.attemptid = qa.uniqueid AND qns$qid.questionid = :qid$qid ";
                        $this->sql->from .=  "LEFT JOIN  {question_states} qs$qid ON qs$qid.id = qns$qid.newgraded ";
                        $this->sql->params['qid'.$qid] = $qid;
                    }
                }
            } else {
                //unset any sort columns that sort on question grade as the
                //grades are not being fetched as fields
                $sess = &$this->sess;
                foreach($sess->sortby as $column => $order) {
                    if (preg_match('/^qsgrade([0-9]+)/', trim($column))){
                        unset($sess->sortby[$column]);
                    }
                }
            }
        }
        parent::query_db($pagesize, $useinitialsbar);
        //get all the attempt ids we want to display on this page
        //or to export for download.
        if (!$this->is_downloading()) {
            $attemptids = array();
            foreach ($this->rawdata as $attempt){
                if ($attempt->attemptuniqueid > 0){
                    $attemptids[] = $attempt->attemptuniqueid;
                }
            }
            $this->gradedstatesbyattempt = quiz_get_newgraded_states($attemptids, true, 'qs.id, qs.grade, qs.event, qs.question, qs.attempt');
            if (has_capability('mod/quiz:regrade', $this->context)){
                $this->regradedqs = quiz_get_regraded_qs($attemptids);
            }
	
        } else {
		
            $this->gradedstatesbyattempt = quiz_get_newgraded_states($this->sql, true, 'qs.id, qs.grade, qs.event, qs.question, qs.attempt');
            if (has_capability('mod/quiz:regrade', $this->context)){
                $this->regradedqs = quiz_get_regraded_qs($this->sql);
            }
        }
    }
}


<?php

/* @command cg
 * @description Search the LSA Course Guide for information about classes
 * @usage cg [course] [distribution requirements]
 *
 * @command courseguide
 * @aliases cg
 */


register_command('cg', 'cg_process_query');
register_command('courseguide', 'cg_process_query');

function cg_process_query($query) {
	$query = strtolower($query);
	
	$cg_main_url = 'http://www.lsa.umich.edu/cg/default.aspx';
	$cg_search_url = 'http://www.lsa.umich.edu/cg/cg_results.aspx?';
	
	if (!$query || !strlen($query)) {
		return $cg_main_url;
	}
	
	$query_terms = array_diff(explode(' ', $query), ['']);
	
	$cg_search_url .= 'termArray=' . getTerm($query_terms);
	$cg_search_url .= '&show=20';
	$cg_search_url .= getDistributionRequirements($query_terms);
	$cg_search_url .= getCourse($query_terms);
	//$cg_search_url .= getInstructor($query_terms);
	
	return $cg_search_url;
}


function getCurrentTerm() {
	// TODO: Build an automated system for this
	return 'f_17_2160';
}

function getTerm($query_terms) {
	// TODO: Parse info from query if applicable
	// &termArray=[term], repeatable
	return getCurrentTerm();
}

function getCourse(&$query_terms) {
  // CASE 1: Multiple Terms ==> All departments, ignore numbers
  if ( count($query_terms) > 2 ) {
    $departmentString = '';
    foreach ($query_terms as $department) {
      $departmentString .= '&department=' . strtoupper($department);
    }
    return $departmentString;
  }
  
  // CASE 2: Two Terms ==> Department and number, space-separated
  else if ( count($query_terms) === 2 ) {
    $departmentString = '&department=' . strtoupper($query_terms[0]);
    $numberString = '&catalog=' . $query_terms[1];
    return $departmentString . $numberString;
  }
  
  // CASE 3: One Term ==> Department and number, one term
  else if ( count($query_terms) === 1 ) {
    $characters = str_split($query_terms[0]);
    $departmentString = '&department=';
    $numberString = '&catalog=';
    foreach($characters as $c) {
      if ($c >= 'a' && $c <= 'z') {
        // It's an alphabet
        $departmentString .= strtoupper($c);
      }
      else {
        $numberString .= $c;
      }
    }
    return $departmentString . $numberString;
  }
  
  // CASE 4: Zero Terms left ==> No course specified
  return '';
}

function getDistributionRequirements(&$query_terms) {
	// &dist=CE/HU/ID/MSA/NS/SS, repeatable
	$distributionRequirements = ['ce', 'hu', 'id', 'msa', 'ns', 'ss'];
	$distributionRequirementsInQueryTerms = [];
	foreach ($distributionRequirements as $requirement) {
	  if ( in_array($requirement, $query_terms) ) {
	    array_push($distributionRequirementsInQueryTerms, $requirement);
	  }
	}
	
	// Remove all distribution requirements
	$query_terms = array_diff($query_terms, $distributionRequirements);
	
	$distributionString = '';
	if ( count($distributionRequirementsInQueryTerms) ) {
	  foreach ($distributionRequirementsInQueryTerms as $requirement) {
	    $distributionString .= '&dist=' . strtoupper($requirement);
	  }
	}
	return $distributionString;
}

/*function getInstructor($query_terms) {
	// TODO: Parse info from query if applicable
	// &instr=[instructor]
	return '';
}*/

?>

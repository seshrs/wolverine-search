<?php

final class CourseGuide implements ICommandController {
  private static $SEASON_METADATA = [
    CourseGuideSeasons::WINTER => [
      'season_code' => 'w',
      'season_offset' => 0,
    ],
    CourseGuideSeasons::SPRING => [
      'season_code' => 'sp',
      'season_offset' => 10,
    ],
    CourseGuideSeasons::SPRING_SUMMER => [
      'season_code' => 'ss',
      'season_offset' => 20,
    ],
    CourseGuideSeasons::SUMMER => [
      'season_code' => 'su',
      'season_offset' => 30,
    ],
    CourseGuideSeasons::FALL => [
      'season_code' => 'f',
      'season_offset' => 40,
    ],
  ];

  private static $cgMainURL = 'http://www.lsa.umich.edu/cg/default.aspx';
  private static $cgSearchURL = 'http://www.lsa.umich.edu/cg/cg_results.aspx?';

  public static function executeQuery($query) {
    if (!$query || !strlen($query)) {
      return self::defaultResult();
    }
    
    $query_terms = array_diff(explode(' ', strtolower($query)), ['']);

    $final_search_url = 
      $cgSearchURL . implode(
        '&',
        [
          self::getTermFragment($query_terms),
          self::getNumResultsFragment($query_terms),
          self::getDistributionRequirementsFragment($query_terms),
          self::getCourse($query_terms),
        ]
      );
    return (new Result)
      ->setURL($final_search_url);
  }

  private static function getTermFragment(&$query_terms) {
    $season = null;
    $season_term_index = -1;
    $new_query_terms = [];

    for ($i = 0; $i < count($query_terms); $i++) {
      $query_term = $query_terms[$i];
      switch ($query_term) {
        case 'f':
        case 'fa':
        case 'fall':
          $season = CourseGuideSeasons::FALL;
          $season_term_index = $i;
          break;
        
          case 'w':
          case 'wn':
          case 'winter':
            $season = CourseGuideSeasons::WINTER;
            $season_term_index = $i;
            break;
          
          case 'sp':
          case 'spring':
            if ($season === 'su') {
              $season = CourseGuideSeasons::SPRING_SUMMER;
            }
            else {
              $season = CourseGuideSeasons::SPRING;
            }
            $season_term_index = $i;
            break;
          
          case 'su':
          case 'summer':
            if ($season === 'sp') {
              $season = CourseGuideSeasons::SPRING_SUMMER;
            }
            else {
              $season = CourseGuideSeasons::SUMMER;
            }
            $season_term_index = $i;
            break;

          case 'ss':
            $season = CourseGuideSeasons::SPRING_SUMMER;
            $season_term_index = $i;
            break;
          
          default:
            array_push($new_query_terms, $query_term);
      }
    }

    if ($season === null || $season_term_index === -1) {
      return self::getCurrentTermFragment();
    }

    $possible_year = $season_term_index < count($query_terms) 
      ? $query_terms[$season_term_index + 1]
      : null;
    $is_possible_year_viable = false;
    if (is_numeric($possible_year)) {
      $possible_year = intval($possible_year);
      if ($possible_year > 2004 && $possible_year < 2050) {
        $is_possible_year_viable = true;
        $year = $possible_year % 100;
      }
      else if ($possible_year > 4 && $possible_year < 50) {
        $is_possible_year_viable = true;
        $year = $possible_year;
      }
      else {
        $year = self::getCurrentYear();
      }
    }
    else {
      $year = self::getCurrentYear();
    }

    // Remove the year from the query terms if applicable
    if ($is_possible_year_viable) {
      $new_query_terms = array_diff(
        $new_query_terms,
        [
          $query_terms[$season_term_index + 1]
        ]
      );
    }
    $query_terms = $new_query_terms;

    return 'termArray=' . self::getTermCode($season, $year);
  }

  private static function getCurrentTermFragment() {
    $month = self::getCurrentMonth();
    $year = self::getCurrentYear();
    if ($month < 3) {
      $season = CourseGuideSeasons::WINTER;
    }
    else if ($month < 11) {
      $season = CourseGuideSeasons::FALL;
    }
    else {
      $season = CourseGuideSeasons::WINTER;
      $year = ($year + 1) % 100;
    }
    return 'termArray=' . self::getTermCode($season, $year);
  }

  private static function getCurrentYear() {
    return intval(date('Y')) % 100;
  }

  private static function getCurrentMonth() {
    return intval(date('m'));
  }

  private static function getTermCode($season, $year) {
    switch ($season) {
      case CourseGuideSeasons::WINTER:
      case CourseGuideSeasons::SPRING:
      case CourseGuideSeasons::SPRING_SUMMER:
      case CourseGuideSeasons::SUMMER:
      case CourseGuideSeasons::FALL:
        break;
      
      default:
        throw new Exception('Invalid season ' . $season . ' while fetching term code. Please report this error to the admin.');
    }

    $season_code = self::$SEASON_METADATA[$season]['season_code'];
    $season_offset = self::$SEASON_METADATA[$season]['season_offset'];
    $year = intval($year);
    if (!$year || $year < 4 || $year > 50) {
      throw new Exception('Invalid year ' . $year . ' while fetching term code. Please report this error to the admin.');
    }

    $term_numeric_code = (($year - 4) * 50 + 1470) + $season_offset;
    $year = $year < 10 ? '0' . strval($year) : strval($year);
    return implode('_', [$season_code, $year, $term_numeric_code]);
  }
  
  private static function getNumResultsFragment(&$query_terms) {
    return 'show=20';
  }

  private static function getDistributionRequirementsFragment(&$query_terms) {
    // &dist=CE/HU/ID/MSA/NS/SS, repeatable
    $distributionRequirements = ['ce', 'hu', 'id', 'msa', 'ns', 'ss'];
    $distributionRequirementsInQueryTerms = [];
    foreach ($distributionRequirements as $requirement) {
      if (in_array($requirement, $query_terms)) {
        array_push($distributionRequirementsInQueryTerms, $requirement);
      }
    }
    
    // Remove all distribution requirements from query terms
    $query_terms = array_diff($query_terms, $distributionRequirements);
    
    foreach ($distributionRequirementsInQueryTerms as &$requirement) {
      $requirement = 'dist=' . strtoupper($requirement);
    }
    return implode('&', $distributionRequirementsInQueryTerms);
  }

  private static function getCourse(&$query_terms) {
    // CASE 1: Multiple Terms ==> All departments, ignore numbers
    if (count($query_terms) > 2) {
      $departmentString = '';
      foreach ($query_terms as $department) {
        $departmentString .= '&department=' . strtoupper($department);
      }
      return $departmentString;
    }
    
    // CASE 2: Two Terms ==> Department and number, space-separated
    else if (count($query_terms) === 2) {
      $departmentString = '&department=' . strtoupper($query_terms[0]);
      $numberString = '&catalog=' . $query_terms[1];
      return $departmentString . $numberString;
    }
    
    // CASE 3: One Term ==> Department and number, one term
    else if (count($query_terms) === 1) {
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

  private static function defaultResult() {
    return (new Result())
      ->setURL(self::$cgMainURL);
  }
}

abstract class CourseGuideSeasons {
  const WINTER = 'WINTER';
  const SPRING = 'SPRING';
  const SPRING_SUMMER = 'SPRING_SUMMER';
  const SUMMER = 'SUMMER';
  const FALL = 'FALL';
}

?>
### LSA Course Guide (`cg`)
*Usage:* `cg [[class] [term] [requirements]]`
*Aliases:* `courseguide`, `course-guide`, `course_guide`

If parameters are blank (or cannot be parsed), will redirect to Course Guide's
home page at https://lsa.umich.edu/cg.

**Parameters:**:

- `[class]`: A combination of department and an optional course number. May be separated by a space. Examples:
  - `eecs 280`
  - `engr101`
  - `polsci`
- `[term]`: A combination of semester and year. If unspecified, defaults to the current/upcoming semester, or current year. Examples:
  - `fall 2016`
  - `wn 2017`
  - `spring summer`
- `[requirements]`: An LSA distribution requirement. Must be from the following list:
  - `ce`: Creative Expression
  - `hu`: Humanities
  - `id`: Interdisciplinary Course
  - `msa`: Math and Symbolic Analysis
  - `ns`: Natural Sciences
  - `ss`: Social Sciences

**Example Usage:**

- `cg eecs280` – Search for classes named EECS 280
- `cg phil` – Search for classes in the PHIL department
- `cg german hu` – Search for GERMAN classes that meet the Humanities ('HU') requirement
- `cg engr 101 winter 2016` - Search for ENGR 101 offered in Winter 2016
- `cg` – Visit the Course Guide's search page for more advanced search options.

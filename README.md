# PbSearch
PHP Library for scraping Google and Yahoo results through navigating via XPaths.

## Usage & Installation

### Installation
Install using composer. Add the respository to your composer.json i.e

```
 "repositories": {
        "repo-name": {
            "type": "vcs",
            "url": "https://github.com/arshad10244/PbSearch"
        }
    }
```

Then run 

```
composer require arshad/pb-search @dev

```


### Usage

```
$results = new Arshad\PbSearch\PbSearch();
$data = $results->search(["your keywords1","your keyword 2"]);

```

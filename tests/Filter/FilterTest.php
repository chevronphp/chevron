<?php

require_once("tests/bootstrap.php");

FUnit::test("Filter::scalarControlChars()", function(){
    $data     = "4e864\n4e993\x08e422743\x0739ebf\x0D7504a\x1A893b0";
    $expected = "4e864\n4e993 e422743 39ebf 7504a 893b0";

    // scalar
    $filter = new \Chevron\Filter\Filter;
    $filtered = $filter->scalarControlChars($data);
    FUnit::equal($filtered, $expected);
});

FUnit::test("Filter::arrayControlChars()", function(){
    $data = array(
        0        => "4e864\n4e99\x083e422743\x0739ebf\x0D7504a\x1A893b0",
        "string" => "4e864\n4e993\x08e422743\x0739ebf\x0D7504a\x1A893b0",
    );
    $expected = array(
        0        => "4e864\n4e99 3e422743 39ebf 7504a 893b0",
        "string" => "4e864\n4e993 e422743 39ebf 7504a 893b0",
    );

    // array
    $filter = new \Chevron\Filter\Filter;
    $filtered = $filter->arrayControlChars($data);
    FUnit::equal($filtered, $expected);
});

FUnit::test("Filter::arrayControlChars() recursive", function(){
    $data = array(
        0        => "4e864\n4e99\x083e422743\x0739ebf\x0D7504a\x1A893b0",
        "string" => "4e864\n4e993\x08e422743\x0739ebf\x0D7504a\x1A893b0",
        "array" => array(
            "string" => "4e864\n4e993e42\x08274339\x07ebf\x0D7504a\x1A893b0",
            "array" => array(
                "string" => "4e864\n4\x08e993e42274339ebf\x07\x0D7504a\x1A893b0",
            ),
        ),
    );

    $expected = array(
        0        => "4e864\n4e99 3e422743 39ebf 7504a 893b0",
        "string" => "4e864\n4e993 e422743 39ebf 7504a 893b0",
        "array" => array(
            "string" => "4e864\n4e993e42 274339 ebf 7504a 893b0",
            "array" => array(
                "string" => "4e864\n4 e993e42274339ebf  7504a 893b0",
            ),
        ),
    );

    // recursive array
    $filter = new \Chevron\Filter\Filter;
    $filtered = $filter->arrayControlChars($data);
    FUnit::equal($filtered, $expected);
});


FUnit::test("Filter::normalizeGlobalFiles() w/ a single field & single value", function(){

    $input["fieldname"] = array(
        'name'     => "field",
        'type'     => "plain/text",
        'size'     => "1024",
        'tmp_name' => "asdfqwerty",
        'error'    => "0",
    );

    $filter = new \Chevron\Filter\Filter;
    $output = $filter->normalizeGlobalFiles($input);

    $expected = array(
        "fieldname" => array(
            array(
                'name'     => "field",
                'type'     => "plain/text",
                'size'     => "1024",
                'tmp_name' => "asdfqwerty",
                'error'    => "0",
            )
        )
    );

    FUnit::equal($expected, $output);

});

FUnit::test("Filter::normalizeGlobalFiles() w/ a single field & multiple values", function(){

    $input["fieldname"] = array(
        'name'     => array("field1", "field2"),
        'type'     => array("plain/text", "plain/text"),
        'size'     => array("1024", "1024"),
        'tmp_name' => array("asdfqwerty1", "asdfqwerty2"),
        'error'    => array("0", "0"),
    );

    $filter = new \Chevron\Filter\Filter;
    $output = $filter->normalizeGlobalFiles($input);

    $expected = array(
        "fieldname" => array(
            array(
                'name'     => "field1",
                'type'     => "plain/text",
                'size'     => "1024",
                'tmp_name' => "asdfqwerty1",
                'error'    => "0",
            ),
            array(
                'name'     => "field2",
                'type'     => "plain/text",
                'size'     => "1024",
                'tmp_name' => "asdfqwerty2",
                'error'    => "0",
            )
        )
    );

    FUnit::equal($expected, $output);

});

FUnit::test("Filter::normalizeGlobalFiles() w/ a multiple fields & mixed values", function(){

    $input = array(
        "fieldname1" => array(
            'name'     => "field",
            'type'     => "plain/text",
            'size'     => "1024",
            'tmp_name' => "asdfqwerty",
            'error'    => "0",
        ),
        "fieldname2" => array(
            'name'     => array("field1", "field2"),
            'type'     => array("plain/text", "plain/text"),
            'size'     => array("1024", "1024"),
            'tmp_name' => array("asdfqwerty1", "asdfqwerty2"),
            'error'    => array("0", "0"),
        )
    );

    $filter = new \Chevron\Filter\Filter;
    $output = $filter->normalizeGlobalFiles($input);

    $expected = array(
        "fieldname1" => array(
            array(
                'name'     => "field",
                'type'     => "plain/text",
                'size'     => "1024",
                'tmp_name' => "asdfqwerty",
                'error'    => "0",
            )
        ),
        "fieldname2" => array(
            array(
                'name'     => "field1",
                'type'     => "plain/text",
                'size'     => "1024",
                'tmp_name' => "asdfqwerty1",
                'error'    => "0",
            ),
            array(
                'name'     => "field2",
                'type'     => "plain/text",
                'size'     => "1024",
                'tmp_name' => "asdfqwerty2",
                'error'    => "0",
            )
        )
    );

    FUnit::equal($expected, $output);

});


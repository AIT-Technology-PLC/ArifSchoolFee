<?php

function userCompany()
{
    return auth()->user()->employee->company;
}

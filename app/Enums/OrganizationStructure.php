<?php
namespace App\Enums;

enum OrganizationStructure: string
{
    case Subsidiary = 'Subsidiary';
    case ZonalOffices = 'Zonal Offices';
    case Branches = 'Branches';
    case Departments = 'Departments';
    case Units = 'Units';
}

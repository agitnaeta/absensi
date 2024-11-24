<?php

namespace App\Services\Acc;

class AccTransaction
{
    public string $type;

    public string $date;

    public string $amount;
    public string $description;
    public string $source_id;
    public string $destination_id;
    public string $tags;
    public string $notes;
    public string $internal_reference;
    public string $external_id;

}

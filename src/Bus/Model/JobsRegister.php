<?php

namespace MBM\Bus\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class JobsRegister extends Eloquent
{
    protected $connection = 'mysql';
    protected $table = 'jobs_register';

    protected $primaryKey = 'checksum';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'checksum' => 'string',
    ];

    protected $fillable = ['checksum', 'job'];

    public static function registerJob($job, $checksum)
    {
        return self::firstOrCreate([
            'checksum'   => $checksum,
            'job'        => $job
        ]);
    }

    public static function unregisterJob($id)
    {
        return self::destroy($id);
    }
}
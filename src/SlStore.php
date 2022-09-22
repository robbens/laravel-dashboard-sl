<?php

namespace Robbens\SlTile;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\Dashboard\Models\Tile;

class SlStore
{
    private Tile $tile;

    public static function make()
    {
        return new static();
    }

    public function __construct()
    {
        $this->tile = Tile::firstOrCreateForName('slTile');
    }

    public function setData(array $data): self
    {
        $this->tile->putData('realtimedeparturesV4', $data);

        return $this;
    }

    public function getData(): Collection
    {
        return collect($this->tile->getData('realtimedeparturesV4'))
            ->mapWithKeys(function ($value, $key) {
                $modeConfig = config('dashboard.tiles.sl.transport_modes.'.$key);
                $totalLimit = $modeConfig['total_limit'];
                $earlyDepartureLimit = $modeConfig['early_departures_limit'];

                if (!$modeConfig) {
                    return false;
                }

                $limit = $totalLimit === false ? 0 : ($totalLimit ?? 5);

                return [$key => collect($value)->filter(function ($item) use ($earlyDepartureLimit) {
                    $departureInMinutes = Carbon::make($item['ExpectedDateTime'])->diffInMinutes();

                    if (!$earlyDepartureLimit) {
                        return true;
                    }

                    if ($item['DisplayTime'] === 'Nu') {
                        return false;
                    }

                    if ($departureInMinutes <= $earlyDepartureLimit) {
                        return false;
                    }

                    return true;
                })->take($limit)];
            });
    }
}

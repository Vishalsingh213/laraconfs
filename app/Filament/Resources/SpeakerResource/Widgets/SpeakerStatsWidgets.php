<?php

namespace App\Filament\Resources\SpeakerResource\Widgets;

use App\Filament\Resources\SpeakerResource\Pages\ListSpeakers;
use App\Models\Speaker;
use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SpeakerStatsWidgets extends BaseWidget
{
    // protected static string $view = 'filament.resources.speaker-resource.widgets.speaker-stats-widgets';
    // use InteractsWithPageTable;

    // protected function getTablePage():string
    // {
    //     return ListSpeakers::class;
    // }

    // protected function getColumns():int
    // {
    //     return 2;
    // }
    protected function getStats(): array
    {
        $speakerCount = Speaker::count();

        // Get the number of duplicate speakers by name
        $duplicateSpeakerCount = Speaker::select('name')
            ->groupBy('name')
            ->having(DB::raw('count(name)'), '>', 1)
            ->get()
            ->count();

        // Get the number of speakers created today
        $speakersCreatedToday = Speaker::whereDate('created_at', Carbon::today())->count();

        return [
            Stat::make('Speaker Count', $speakerCount)
                ->description('Total Number Of Speakers')
                ->descriptionIcon('heroicon-o-user-group'),

            Stat::make('Duplicate Speakers', $duplicateSpeakerCount)
                ->description('Speakers with the same name')
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->color('danger'),

            Stat::make('Speakers Created Today', $speakersCreatedToday)
                ->description('Speakers added today')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('success'),
        ];
    }
}

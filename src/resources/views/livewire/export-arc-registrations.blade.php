<div>
    <button class="btn-outline-info btn" wire:click="export()">Export excel</button>
    @if($this->batch)
        <div style="margin-top: 1%; margin-left: 2%; color: black; border-left: 3px solid #f3f3f3; padding: 1%; font-weight: bolder;">
            @if($finished)
                @if(! $error)
                    <h4 class="mt-1 mr-2">Finished</h4>
                    <a href="{{ url('storage/'.$downloadLink) }}"><h5>Download {{$downloadLink}}</h5></a>
                @else
                    <h4 class="mt-1 mr-2" style="color: rgba(255, 110, 110, 0.815);">Error ! please view log file to fix bug</h4>
                @endif
            @else
                <div wire:poll="checkStatus">
                    <h4>Generating <img style='width: 50px' src='{{ url("storage/loading.gif") }}'></h4>
                </div>
            @endif
        </div>
    @endif
</div>

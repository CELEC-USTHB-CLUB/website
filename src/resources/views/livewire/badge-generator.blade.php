<div>
    <button class="btn btn-primary btn-add-new" wire:click="generate">
        <i class="voyager-list"></i> Export badges
    </button>
    @if($this->batch)
        <div style="margin-top: 1%; margin-left: 2%; color: black; border-left: 3px solid #f3f3f3; padding: 1%; font-weight: bolder;">
            @if($finished)
                <h4 class="mt-1 mr-2">Finished</h4>
                {{-- <a href="{{ url('storage/'.$invitationsZipPath) }}"><h5>Download {{$invitationsZipPath}}</h5></a> --}}
            @else
                <div wire:poll="checkStatus">
                    <h4>Generating <img style='width: 50px' src='{{ url("storage/loading.gif") }}'></h4>
                </div>
            @endif
        </div>
    @else
    @endif
</div>

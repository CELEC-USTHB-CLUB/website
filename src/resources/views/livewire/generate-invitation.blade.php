<div>
    @if($this->batch)
        <div style="margin-top: 1%; margin-left: 2%; color: black; border-left: 3px solid #f3f3f3; padding: 1%; font-weight: bolder;">
            @if($finished)
                @if(! $error)
                    <h4 class="mt-1 mr-2">Finished</h4>
                    <a href="{{ url('storage/'.$invitationsZipPath) }}"><h5>Download {{$invitationsZipPath}}</h5></a>
                @else
                    <h4 class="mt-1 mr-2" style="color: rgba(255, 110, 110, 0.815);">Error ! please view log file to fix bug</h4>
                @endif
            @else
                <div wire:poll="checkStatus">
                    <h4>Generating <img style='width: 50px' src='{{ url("storage/loading.gif") }}'></h4>
                </div>
            @endif
        </div>
    @else
        @if($invitationsZipPath)
            <div style="margin-top: 1%; margin-left: 2%; color: black; border-left: 3px solid #f3f3f3; padding: 1%; font-weight: bolder;">
                <a href="{{ url('storage/'.$invitationsZipPath) }}"><h5>Download {{$invitationsZipPath}}</h5></a>
            </div>
        @endif
    @endif
    <form method="POST" action="{{ url('admin/trainings/invitations') }}" enctype="multipart/form-data" wire:submit.prevent="submit">
        @csrf
        <h3 class="panel-title">Import users list (excel file)</h3>
        <input style="width: 100%;" wire:model="excel" type="file" class="input-control" name="file">
        <input type="hidden" value="{{ $training_id }}" class="input-control" name="id">
        <br/>
        <button class="btn btn-success" style="margin-left: 1%">Generate invitations</button>
    </form>
</div>
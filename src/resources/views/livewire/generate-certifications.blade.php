<div>
    <hr/>
    @if($this->batch)
        <div style="margin-top: 1%; margin-left: 2%; color: black; border-left: 3px solid #f3f3f3; padding: 1%; font-weight: bolder;">
            @if($finished)
                @if(! $error)
                    <h4 class="mt-1 mr-2">Finished</h4>
                    <a href="{{ url('storage/'.$certificationsZipPath) }}"><h5>Download {{$certificationsZipPath}}</h5></a>
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
        @if($certificationsZipPath)
            <div style="margin-top: 1%; margin-left: 2%; color: black; border-left: 3px solid #f3f3f3; padding: 1%; font-weight: bolder;">
                <a href="{{ url('storage/'.$certificationsZipPath) }}"><h5>Download {{$certificationsZipPath}}</h5></a>
            </div>
        @endif
    @endif
    <div wire:loading wire:target="certification">
        <h4>Uploading template, please wait <img style='width: 50px' src='{{ url("storage/loading.gif") }}'></h4>
    </div>
    <h3 class="panel-title">Generate certifications: </h3> 
    <form method="POST" accept="#" enctype="multipart/form-data" wire:submit.prevent="submit">
        <h4 class="panel-title">Import users list (excel file) Column 1:A To N:A must contains only full name</h4>
        <input style="width: 100%;" wire:model="excel" type="file" class="input-control" name="file">
        <h4 class="panel-title">Import certification template</h4>
        <input style="width: 100%;" wire:model="certification" type="file" class="input-control" name="file">
        <br/>
        <button class="btn btn-success" style="margin-left: 1%">Generate certifications</button>
    </form>
</div>

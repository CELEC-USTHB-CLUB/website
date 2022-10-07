<div>
    @if($this->batch)
    <div style="margin-top: 1%; margin-left: 1%; color: black; background-color: #f3f3f3; padding: 1%;">
        @if($finished)
            <h4 class="mt-1 mr-2">Finished</h4>
        @else
            <div wire:poll="checkStatus">
                <h4>Generating ...</h4>
            </div>
        @endif
    </div>
    @endif
    <form method="POST" action="{{ url('admin/trainings/invitations') }}" enctype="multipart/form-data" wire:submit.prevent="submit">
        @csrf
        <h3 class="panel-title">Import users list</h3>
        <input wire:model="excel" type="file" class="input-control" name="file">
        <input type="hidden" value="{{ $training_id }}" class="input-control" name="id">
        <button class="btn btn-success">Generate invitations</button>
    </form>
</div>
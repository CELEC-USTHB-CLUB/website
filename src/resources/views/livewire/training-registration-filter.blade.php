<div style="margin: 2%;">
    <h3>Filter "{{$training->title}}" registrations: </h3>  
    <textarea wire:model.debounce.500ms="filters" class="form-control" style="max-width: 100%; height: 200px;"></textarea>
    <button class="btn btn-success" wire:click="generate()">Download</button>
</div>

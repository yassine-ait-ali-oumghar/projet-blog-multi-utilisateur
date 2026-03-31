@extends('layaout.app')
@section('title') Create @endsection

@section('content')
<form action="{{ route('posts.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="titre" class="form-label">Titre</label>
        <input 
            type="text" 
            class="form-control" 
            id="titre" 
            name="titre"
            placeholder="Entrez le titre..."
        >
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea 
            class="form-control" 
            id="description" 
            name="description"
            rows="5"
            placeholder="Entrez votre description ici..."
            style="resize: vertical; min-height: 120px;"
        ></textarea>
    </div>
    <div class="mb-3">
        <label for="auteur" class="form-label">Post Créé par</label>
        <select class="form-control" id="auteur" name="auteur">
            <option value="">-- Choisir un auteur --</option>
            <option value="Yassine">Yassine</option>
            <option value="Mourad">Mourad</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>

</form>
@endsection
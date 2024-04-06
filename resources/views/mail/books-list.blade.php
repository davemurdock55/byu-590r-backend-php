Hello app owner. Here is the list of all the books available on Book Haven:

<table>
	<thead>
		<tr>
			<th>Title</th>
			<th>Series</th>
			<th>Author ID</th>
			<th>Cover</th>
			<th>Description</th>
			<th>Date Published</th>
			<th>Created At</th>
			<th>Updated At</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($books as $book)
		<tr>
			<td>{{$book->title}}</td>
			<td>{{$book->series}}</td>
			<td>{{$book->author_id}}</td>
			<td>{{$book->cover}}</td>
			<td>{{$book->description}}</td>
			<td>{{$book->date_published}}</td>
			<td>{{$book->created_at}}</td>
			<td>{{$book->updated_at}}</td>


		</tr>
		@endforeach

	</tbody>
</table>
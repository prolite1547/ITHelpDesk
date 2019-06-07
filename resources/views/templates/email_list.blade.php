<caption>Emails</caption>
@foreach($emails as $mail)
<tr>
    <td>{{$mail->email->email}}</td>
    <td><span class="form-emailGroupAdd__remove-mail" id="{{$mail->id}}">X</span></td>
</tr>
@endforeach

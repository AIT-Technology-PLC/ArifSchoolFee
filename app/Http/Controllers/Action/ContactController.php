<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\ContactImport;
use App\Models\Contact;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Contact Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Contact::class);

        ini_set('max_execution_time', '-1');

        (new ContactImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}

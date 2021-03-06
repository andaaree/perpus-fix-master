<?php

namespace App\Http\Controllers;

use App\Book;
use stdClass;
use App\Grade;
use App\Major;
use App\Subject;
use App\Education;
use Spatie\PdfToImage\Pdf;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Org_Heigl\Ghostscript\Ghostscript;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $edu = Education::all();
        $maj = Major::all();
        $sub = Subject::all();
        $kls = Grade::all();
        return view('book.index',compact('edu','maj','sub', 'kls'));
    }

    public function data()
    {
        
        return DataTables::of(
            Book::with(['getEdu', 'getGrade'])
            ->select('id', 'title', 'desc', 'clicked_time', 'published_year', 'publisher', 'author')
            ->orderBy('updated_at','desc')
        )
            ->addIndexColumn()
            ->setRowId('id')
            ->toJson();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $res = new stdClass;
        $request->validate([
            'filebook' => 'required|file',
            'jenjang' => 'required',
            'kelas' => 'required',
            'jurusan' => 'required',
            'mapel' => '',
            'judul' => 'required',
            'desc' => '',
            'tahun' => 'required',
            'penerbit' => 'required',
            'pengarang' => 'required'
        ]);
        $file = $request->file('filebook');
        if ($file != null) {
            $filename = Str::slug("$request->judul-$request->pengarang-$request->tahun");
            $fixname = "$filename.".$file->getClientOriginalExtension();
            $thumbname = "$filename.png";
            $ss = $file->storeAs('public\pdf',$fixname);
            if ($ss) {
                // if (str_contains(PHP_OS, 'WIN')) {
                //     Ghostscript::setGsPath(public_path('gs/win/bin/gswin64c.exe'));
                // }else{
                //     Ghostscript::setGsPath(public_path('gs/lin/gs-9561-linux-x86_64'));
                // }   
                $pdf = new Pdf(storage_path('app/public/pdf/'.$fixname));
                $saved = $pdf->saveImage(storage_path('app/public/thumb/pdf/').$thumbname);
                if ($saved) {
                    $imgman = new ImageManager(['driver'=> 'imagick']);
                    $imgman->make(storage_path('app/public/thumb/pdf/').$thumbname)->resize(144,208)->save();
                    $book = new Book;
                    $book->title = $request->judul;
                    $book->desc = $request->desc;
                    $book->filename = $fixname;
                    $book->thumb = $thumbname;
                    $book->filetype = $file->getClientOriginalExtension();
                    $book->clicked_time = 0;
                    $book->edu_id= $request->jenjang;
                    $book->grade_id= $request->kelas;
                    $book->major_id= $request->jurusan;
                    $book->sub_id= $request->mapel;
                    $book->published_year = $request->tahun;
                    $book->publisher = $request->penerbit;
                    $book->author = $request->pengarang;
                    $book->save();
                    
                    $res->status = "success";
                    $res->title = "Berhasil";
                    $res->message = "Buku berhasil ditambahkan";
                }else {
                    $res->status = "error";
                    $res->title = "Gagal";
                    $res->message = "Buku gagal ditambah";
                }
                return redirect()->route('buku.index')->with($res->status,json_encode($res));
            } else {
                $res->status = "error";
                $res->title = "Gagal";
                $res->message = 'Gagal menyimpan file';
                return redirect()->route('buku.index')->with($res->status,json_encode($res,400));
            }
        }else {
            $res->status = "error";
            $res->title = "Gagal";
            $res->message = 'File tidak ter-upload.';
            return redirect()->route('buku.index')->with($res->status,json_encode($res,404));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $buku)
    {
        $edu = Education::all();
        $maj = Major::all();
        $sub = Subject::all();
        $kls = Grade::all();

        return view('book.edit',compact('edu','maj','sub','buku', 'kls'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $buku)
    {
        $res = new stdClass;
        $request->validate([
            'jenjang' => 'required',
            'kelas' => 'required',
            'jurusan' => 'required',
            'mapel' => '',
            'judul' => 'required',
            'desc' => '',
            'tahun' => 'required',
            'penerbit' => 'required',
            'pengarang' => 'required'
        ]);

        $title = $request->judul;
        $author = $request->pengarang;
        $year = $request->tahun;
        
        $filename = Str::slug("$title-$author-$year");
        $file = $request->file('filebook');
        
        if ($file != null) {
            $fixname = "$filename.".$file->getClientOriginalExtension();
            $thumbname = $filename.".png";
            
            $opfile = 'public/pdf/'.$buku->filename;
            $op = 'public/thumb/pdf/'.$buku->thumb;
            
            $npfile = 'public/pdf/'.$fixname;
            $np = 'public/thumb/pdf/'.$thumbname;
            $statepdf = file_exists(storage_path($opfile));
            $statethumb = file_exists(storage_path($op));

            if (($title != $buku->title) || ($author != $buku->author) || ($year != $buku->published_year)) {
                Storage::delete($opfile);
                Storage::delete($op);
            }
            
            $sv = $file->storeAs('public\pdf',$fixname);
            if ($sv) {
                $pdf = new Pdf(public_path('storage/pdf/'.$fixname));
                $pdfdir = storage_path('app/public/thumb/pdf/').$thumbname;
                $pdf->saveImage($pdfdir);

                $imgman = new ImageManager(['driver'=> 'imagick']);
                $img = $imgman->make(storage_path('app/public/thumb/pdf/').$thumbname)->resize(144,208)->save();
                $buku->title = $request->judul;
                $buku->desc = $request->desc;
                $buku->filename = $fixname;
                $buku->thumb = $thumbname;
                $buku->edu_id= $request->jenjang;
                $buku->grade_id= $request->kelas;
                $buku->major_id= $request->jurusan;
                $buku->sub_id= $request->mapel;
                $buku->published_year = $request->tahun;
                $buku->publisher = $request->penerbit;
                $buku->author = $request->pengarang;
                $buku->save();

                $res->status = "success";
                $res->title = "Berhasil";
                $res->message = "Buku berhasil di edit";
                return redirect()->route('buku.index')->with($res->status,json_encode($res));
            }else{
                $res->status = "error";
                $res->title = "Gagal";
                $res->message = "Buku gagal di upload";
                return redirect()->route('buku.index')->with($res->status,json_encode($res));
            }
        }else {
            $fixname = $filename.".pdf";
            $opfile = 'public/pdf/'.$buku->filename;
            $op = 'public/thumb/pdf/'.$buku->thumb;
            $thumbname = $filename.".png";
            $npfile = 'public/pdf/'.$fixname;
            $np = 'public/thumb/pdf/'.$thumbname;
            if (($title != $buku->title) || ($author != $buku->author) || ($year != $buku->published_year)) {
                $buku->filename = $fixname;
                $buku->thumb = $thumbname;
                Storage::move($opfile,$npfile);
                Storage::move($op,$np);
            }
            $buku->title = $request->judul;
            $buku->desc = $request->desc;
            
            $buku->edu_id= $request->jenjang;
            $buku->grade_id= $request->kelas;
            $buku->major_id= $request->jurusan;
            $buku->sub_id= $request->mapel;
            $buku->published_year = $request->tahun;
            $buku->publisher = $request->penerbit;
            $buku->author = $request->pengarang;
            $buku->save();

            $res->status = "success";
            $res->title = "Berhasil";
            $res->message = "Buku berhasil di edit";
            return redirect()->route('buku.index')->with($res->status,json_encode($res));
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $buku)
    {
        $res = new stdClass;
        $del = Storage::delete('public/pdf/'.$buku->filename);
        if ($del) {
            Storage::delete('public/thumb/pdf/'.$buku->thumb);
            
            $buku->delete();
            $status = 'success';
            $title = 'Berhasil';
            $msg = 'Hapus buku berhasil.';
        }else{
            $status = 'error';
            $title = 'Gagal';
            $msg = 'Hapus buku gagal.';
        }
        $res->status = $status;
        $res->title = $title;
        $res->message = $msg;
        return response()->json($res);
    }

    public function imports()
    {
        return view('book.import');
    }
    public function excel()
    {
        return view('book.excel');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Externallink;

class ExternalLinkController extends Controller {

    public function externallinks() {
        $external_links = Externallink::orderBy('name')
                ->get();

        return view('pages.admin-side.modules.externallinks.externallinks')->with([
                    'external_links' => $external_links
        ]);
    }

    public function addExternallinks() {
        return view('pages.admin-side.modules.externallinks.addexternallink');
    }

    public function saveExternallinks(Request $request) {
        $this->validate($request, [
            'link' => 'required',
            'tujuan' => 'required'
        ]);

        $link = Externallink::where('name', 'like', $request->link)
                ->first();
        if ($link) {
            return back()->with([
                        'err' => 'Link sudah terpakai. Silahkan gunakan link lain.'
            ]);
        }

        $link = new Externallink;
        $link->name = $request->link;
        $link->link = str_replace(' ', '', $request->link_koreanluxury);
        $link->redirect_to = $request->tujuan;
        $link->save();

        return redirect('extlink')->with([
                    'msg' => 'Link baru telah tersimpan.'
        ]);
    }

    public function editExternallinks($id) {
        $extlink = Externallink::find($id);
        if (!$extlink) {
            return redirect('extlink')->with([
                        'err' => 'Tidak ada link yang ditemukan.'
            ]);
        }

        return view('pages.admin-side.modules.externallinks.editexternallink')->with([
                    'extlink' => $extlink
        ]);
    }

    public function updateExternallinks(Request $request) {
        $this->validate($request, [
            'link' => 'required',
            'tujuan' => 'required'
        ]);

        $link = Externallink::find($request->link_id);
        $link->name = $request->link;
        $link->link = str_replace(' ', '', $request->link_koreanluxury);
        $link->redirect_to = $request->tujuan;
        $link->save();

        return redirect('extlink')->with([
                    'msg' => 'Link berhasil diubah.'
        ]);
    }

    public function deleteExternallinks($id) {
        $extlink = Externallink::find($id);
        if (!$extlink) {
            return redirect('extlink')->with([
                        'err' => 'Tidak ada link yang ditemukan.'
            ]);
        }

        $extlink->delete();

        return redirect('extlink')->with([
                    'msg' => 'Link berhasil dihapus.'
        ]);
    }

    public function redirectExternal($link) {
        $extlink = Externallink::where('link', 'like', $link)
                ->first();
        if (!$extlink) {
            return redirect('home')->with([
                        'err' => 'Tidak ada link yang ditemukan.'
            ]);
        }

        return redirect($extlink->redirect_to);
    }

}

<?php

namespace App\Http\Middleware;

use App\Models\Divisi as ModelsDivisi;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Divisi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,  ...$allowed_roles)
    {
        // return $next($request);

        $allow = array();
        foreach ($allowed_roles as $a) {
            $d = ModelsDivisi::where('kode', $a)->first();
            $allow[] = $d->id;
        }
        $role = strtolower(request()->user()->divisi_id);

        if (in_array($role, $allow)) {
            return $next($request);
        }

        if ($request->user()->hasRole("24")) {
            return redirect('/ppic');
        } else if ($request->user()->hasRole("15")) {
            return redirect('/logistik/dashboard');
        } else if ($request->user()->hasRole("3")) {
            return redirect('/manager-teknik');
        } else if ($request->user()->hasRole("23")) {
            return redirect('/qc/dashboard');
        } else if ($request->user()->hasRole("26")) {
            return redirect('/penjualan/dashboard');
        } else if ($request->user()->hasRole("13")) {
            return redirect('/gbj/dashboard');
        } else if ($request->user()->hasRole("17")) {
            return redirect('/produksi/dashboard');
        } else if ($request->user()->hasRole("12")) {
            return redirect('/gk/dashboard');
        } else if ($request->user()->hasRole("9")) {
            return redirect('/dc/dashboard');
        } else if ($request->user()->hasRole("2")) {
            return redirect('/direksi/dashboard');
        } else if ($request->user()->hasRole("8")) {
            return redirect('/penjualan/dashboard');
        } else if ($request->user()->hasRole("22")) {
            return redirect('/lab/dashboard');
        } else if ($request->user()->hasRole("14")) {
            return redirect('/administrator/dashboard');
        } else if ($request->user()->hasRole("16") || $request->user()->hasRole("10")) {
            return redirect('/mtc/air/masuk');
        } else if ($request->user()->hasRole("30")) {
            return redirect('/meeting/hr');
        }
    }
}

<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Subdomain;

class ThemeController extends Controller
{
    public function index()
    {
        $themes = ['theme1' , 'theme2'];
        $subdomains = Subdomain::all();
        return view('themes.index', compact('themes', 'subdomains'));
    }

    public function deploy(Request $request)
    {
        $request->validate([
            'theme' => 'required',
            'subdomain' => 'required|unique:subdomains,subdomain',
        ]);

        $subdomain = $request->subdomain;
        $theme = $request->theme;

        //Windows cmd
        $command = "docker run -d "
        . "--name {$subdomain} "
        . "--network traefik-network "
        . "--label \"traefik.enable=true\" "
        . "--label \"traefik.http.routers.{$subdomain}.rule=Host(`\"{$subdomain}.localhost\"`)\" "
        . "--label \"traefik.http.services.{$subdomain}.loadbalancer.server.port=80\" "
        . "{$theme}:latest";


// mac and Linux cmd
        // $command = "docker run -d "
        //  . "--name {$subdomain} "
        //  . "--network traefik-network "
        //  . "--label \"traefik.enable=true\" "
        //  . "--label 'traefik.http.routers.{$subdomain}.rule=Host(\"{$subdomain}.localhost\")' "
        //  . "--label \"traefik.http.services.{$subdomain}.loadbalancer.server.port=80\" "
        //  . "{$theme}:latest";




        try {
            // Capture the output and error
            $output = null;
            $resultCode = null;

            exec($command, $output, $resultCode);

            // Check if the command was successful
            if ($resultCode !== 0) {
                // Log or handle error appropriately
                throw new Exception("Failed to deploy container. Output: " . implode("\n", $output));
            }

            // Log output for debugging
            \Log::info("Docker run command executed successfully", $output);

            // Save the subdomain to the database
            Subdomain::create(['subdomain' => $subdomain, 'theme' => $theme]);
            //await for 5 seconds
            sleep(3);
            return redirect("http://{$subdomain}.localhost");
            // return response()->json(['success' => true, 'subdomain' => $subdomain]);

        } catch (Exception $e) {
            // Capture and log the error
            \Log::error("Error in deploy method: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $subdomain = Subdomain::findOrFail($id);

        exec("docker stop {$subdomain->subdomain}");
        exec("docker rm {$subdomain->subdomain}");

        $subdomain->delete();

        return response()->json(['success' => true]);
    }
}


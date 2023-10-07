<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeUploadedFiles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $files = $request->file();

        foreach ($files as $fileFieldName => $uploadedFile) {
            // Ensure the file is of type "text/plain" or other allowed types
            if ($uploadedFile->getMimeType() === 'text/plain') {
                // Read the contents of the uploaded file
                $contents = file_get_contents($uploadedFile->getRealPath());

                // Remove non-UTF-8 characters
                $sanitizedContents = preg_replace('/[^\x{0020}-\x{007E}]/u', '', $contents);

                // Store the sanitized contents back in the file
                file_put_contents($uploadedFile->getRealPath(), $sanitizedContents);
            }
        }

        return $next($request);
    }
}

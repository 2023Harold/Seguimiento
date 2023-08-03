<?php

namespace App\Providers;

use Blade;
use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {        
        Paginator::useBootstrap();
        Blade::directive('btnSubmit', function ($parametros) {
            $parametros_separados = explode(',', $parametros);
            $texto = $parametros_separados[0];
            $class = 'btn btn-primary';
            if (count($parametros_separados) == 2) {
                $btnClass = str_replace("'", '', $parametros_separados[1]);
                if ($btnClass == ' ') {
                    $class = '';
                } else {
                    $class = 'btn btn-primary '.$btnClass;
                }
            }

            return "<?php echo '<button type=\"submit\" class=\"submit $class\">'.$texto.'</button>'; ?>";
        });

        Blade::directive('btnCancelar', function ($expression) {
            $parametros_separados = explode(',', $expression);
            $text = $parametros_separados[0];
            $url = $parametros_separados[1];
            if (count($parametros_separados) == 3) {
                $url = $parametros_separados[1].', '.$parametros_separados[2];

                return "<?php echo '<a href=\"'.$url.'\" class=\"btn btn-secondary me-2\">'.$text.'</a>'; ?>";
            } else {
                return "<?php echo '<a href=\"'.$url.'\" class=\"btn btn-secondary me-2\">'.$text.'</a>'; ?>";
            }
            /*eval("\$params = [$expression];");
            [$param1, $param2] = $params;
            return "<?php
                echo '<a href=\"$param2\" class=\"btn btn-secondary\">$param1</a>';
                ?>";*/
        });
        Blade::directive('btnConfirmacion', function ($expression) {
            $parametros_separados = explode(',', $expression);
            $text = $parametros_separados[0];
            $url = $parametros_separados[1];
            if (count($parametros_separados) == 3) {
                $url = $parametros_separados[1].', '.$parametros_separados[2];
            }

            return "<?php
            echo '<form method=\"GET\" action=\"'.$url.'\"
                    accept-charset=\"UTF-8\" style=\"display:inline;\"
                    onSubmit=\"return confirm(&quot;¿Desea finalizar el registro?&quot;)\">'.
                    method_field('GET').
                    csrf_field().
                    '<button type=\"submit\" class=\"btn btn-primary ms-2\">'.$text.'</button>'
                .'</form>';
            ?>";
        });
        Blade::directive('destroy', function ($url_destroy) {
            $url_destroy_despues_primer_comilla = Str::after($url_destroy, "'");
            $url_destroy_antes_segunda_comilla = Str::before($url_destroy_despues_primer_comilla, "'");
            $permiso = $url_destroy_antes_segunda_comilla;

            return "<?php
          
                echo '<form method=\"POST\" action=\"'.$url_destroy.'\"
                        accept-charset=\"UTF-8\" style=\"display:inline;\"
                        onSubmit=\"return confirm(&quot;¿Desea eliminar el registro?&quot;)\">'.
                        method_field('DELETE').
                        csrf_field().
                        '<button type=\"submit\" class=\"btn btn-link m-0 p-0 ms-2\"><i class=\"bi bi-trash text-primary fs-3\"></i></button>'
                    .'</form>';
            
            ?>";
        });
        Blade::directive('destroyNoAuth', function ($url_destroy) {
            $url_destroy_despues_primer_comilla = Str::after($url_destroy, "'");
            $url_destroy_antes_segunda_comilla = Str::before($url_destroy_despues_primer_comilla, "'");
            $permiso = $url_destroy_antes_segunda_comilla;

            return "<?php
                echo '<form method=\"POST\" action=\"'.$url_destroy.'\"
                        accept-charset=\"UTF-8\" style=\"display:inline;\"
                        onSubmit=\"return confirm(&quot;¿Desea eliminar el registro?&quot;)\">'.
                        method_field('DELETE').
                        csrf_field().
                        '<button type=\"submit\" class=\"btn btn-link m-0 p-0 mt-n2 ms-2\"><i class=\"bi bi-trash text-primary fs-3\"></i></button>'
                    .'</form>';
            ?>";
        });
        Blade::directive('btnEdit', function ($url) {
            return "<?php
                echo '<a href=\"'.$url.'\"><i class=\"bi bi-pencil-square\"></i></a>';
                ?>";
        });

        /**
         * para botones en general, recibe el texto o icono, la url, y las clases,
         * si no recibe clases, será por defecto un enlace directo sin estilo
         */
        Blade::directive('button', function ($parametros) {
            $parametros_separados = explode(',', $parametros);
            $texto = $parametros_separados[0];
            $url = $parametros_separados[1];
            if (count($parametros_separados) == 2) {
                return "<?php echo '<a href=\"'.$url.'\" class=\"btn btn-primary me-1\">'.$texto.'</a>'; ?>";
            }

            if (count($parametros_separados) == 3) {
                $class = 'btn btn-primary mx-1 ';
                if (str_contains($url, 'create')) {
                    $btnClass = str_replace("'", '', $parametros_separados[2]);
                    if ($btnClass == ' ') {
                        $class = '';
                    } else {
                        $class = 'btn btn-primary mx-1 '.$btnClass;
                    }

                    return "<?php echo '<a href=\"'.$url.'\" class=\"$class\">'.$texto.'</a>'?>";
                } else {
                    if (str_contains($url, 'edit')) {
                        $url = $parametros_separados[1].', '.$parametros_separados[2];

                        return "<?php echo '<a href=\"'.$url.'\" class=\"$class\">'.$texto.'</a>'?>";
                    } else {
                        $btnClass = '';
                        if (str_contains($parametros_separados[2], '$')) {
                            $url = $parametros_separados[1].', '.$parametros_separados[2];
                        } else {
                            $btnClass = str_replace("'", '', $parametros_separados[2]);
                            $url = $parametros_separados[1];
                        }
                        if ($btnClass == ' ') {
                            $class = ' ';
                        } else {
                            $class = 'btn btn-primary mx-1 '.$btnClass;
                        }

                        return "<?php echo '<a href=\"'.$url.'\" class=\"$class\">'.$texto.'</a>'?>";
                    }
                }
            }
            if (count($parametros_separados) == 4) {
                $btnClass = str_replace("'", '', $parametros_separados[3]);
                if ($btnClass == ' ') {
                    $class = '';
                } else {
                    $class = 'btn btn-primary '.$btnClass;
                }
                $url = $parametros_separados[1].', '.$parametros_separados[2];
                //$class = 'btn btn-primary '.str_replace("'", '', $parametros_separados[3]);
                return "<?php echo '<a href=\"'.$url.'\" class=\"$class\">'.$texto.'</a>'?>";
            }
        });

        Blade::directive('buttonCustomCan', function ($parametros) {
            $parametros_separados = explode(',', $parametros);
            $texto = $parametros_separados[0];
            $url = $parametros_separados[1];

            $url_permiso_primer_comilla = Str::after($parametros_separados[1], "'");
            $url_permiso_segunda_comilla = Str::before($url_permiso_primer_comilla, "'");
            $permiso = $url_permiso_segunda_comilla;
            if (count($parametros_separados) == 2) {
                return "<?php if(auth()->user()->can(\"$permiso\")){ echo '<a href=\"'.$url.'\" class=\"btn btn-primary me-1\">'.$texto.'</a>';} ?>";
            }

            if (count($parametros_separados) == 3) {
                $class = 'mx-1 btn btn-primary ';
                if (str_contains($url, 'create')) {
                    $btnClass = str_replace("'", '', $parametros_separados[2]);
                    if ($btnClass == ' ') {
                        $class = '';
                    } else {
                        $class = 'mx-1 btn btn-primary ' . $btnClass;
                    }

                    return "<?php if(auth()->user()->can(\"$permiso\")){ echo '<a href=\"'.$url.'\" class=\"$class\">'.$texto.'</a>';} ?>";
                } else {
                    if (str_contains($url, 'edit')) {
                        $url = $parametros_separados[1] . ', ' . $parametros_separados[2];

                        return "<?php if(auth()->user()->can(\"$permiso\")){ echo '<a href=\"'.$url.'\" class=\"$class\">'.$texto.'</a>'; }?>";
                    } else {
                        $btnClass = '';
                        if (str_contains($parametros_separados[2], '$')) {
                            $url = $parametros_separados[1] . ', ' . $parametros_separados[2];
                        } else {
                            $btnClass = str_replace("'", '', $parametros_separados[2]);
                            $url = $parametros_separados[1];
                        }
                        if ($btnClass == ' ') {
                            $class = '';
                        } else {
                            $class = 'mx-1 btn btn-primary ' . $btnClass;
                        }

                        return "<?php
                                if(auth()->user()->can(\"$permiso\")){
                                    echo '<a href=\"'.$url.'\" class=\"$class\">'.$texto.'</a>';
                                } ?>";
                    }
                }
            }
            if (count($parametros_separados) == 4) {
                $btnClass = str_replace("'", '', $parametros_separados[3]);
                if ($btnClass == ' ') {
                    $class = '';
                } else {
                    $class = ' ' . $btnClass;
                }
                $url = $parametros_separados[1] . ', ' . $parametros_separados[2];

                return "<?php if(auth()->user()->can(\"$permiso\")){ echo '<a href=\"'.$url.'\" class=\"$class\">'.$texto.'</a>'; }?>";
            }
        });

        Blade::directive('buttonCan', function ($parametros) {
            $parametros_separados = explode(',', $parametros);
            $texto = $parametros_separados[0];
            $url = $parametros_separados[1];

            $url_permiso_primer_comilla = Str::after($parametros_separados[1], "'");
            $url_permiso_segunda_comilla = Str::before($url_permiso_primer_comilla, "'");
            $permiso = $url_permiso_segunda_comilla;
            if (count($parametros_separados) == 2) {
                return "<?php if(auth()->user()->can(\"$permiso\")){ echo '<a href=\"'.$url.'\" class=\"btn btn-primary me-1\">'.$texto.'</a>';} ?>";
            }

            if (count($parametros_separados) == 3) {
                $class = 'btn btn-primary mx-1 ';
                if (str_contains($url, 'create')) {
                    $btnClass = str_replace("'", '', $parametros_separados[2]);
                    if ($btnClass == ' ') {
                        $class = '';
                    } else {
                        $class = 'btn btn-primary mx-1 '.$btnClass;
                    }

                    return "<?php if(auth()->user()->can(\"$permiso\")){ echo '<a href=\"'.$url.'\" class=\"$class\">'.$texto.'</a>';} ?>";
                } else {
                    if (str_contains($url, 'edit')) {
                        $url = $parametros_separados[1].', '.$parametros_separados[2];

                        return "<?php if(auth()->user()->can(\"$permiso\")){ echo '<a href=\"'.$url.'\" class=\"$class\">'.$texto.'</a>'; }?>";
                    } else {
                        $btnClass = '';
                        if (str_contains($parametros_separados[2], '$')) {
                            $url = $parametros_separados[1].', '.$parametros_separados[2];
                        } else {
                            $btnClass = str_replace("'", '', $parametros_separados[2]);
                            $url = $parametros_separados[1];
                        }
                        if ($btnClass == ' ') {
                            $class = ' ';
                        } else {
                            $class = 'btn btn-primary mx-1 '.$btnClass;
                        }

                        return "<?php
                                if(auth()->user()->can(\"$permiso\")){
                                    echo '<a href=\"'.$url.'\" class=\"$class\">'.$texto.'</a>';
                                } ?>";
                    }
                }
            }
            if (count($parametros_separados) == 4) {
                $btnClass = str_replace("'", '', $parametros_separados[3]);
                if ($btnClass == ' ') {
                    $class = '';
                } else {
                    $class = 'btn btn-primary '.$btnClass;
                }
                $url = $parametros_separados[1].', '.$parametros_separados[2];

                return "<?php if(auth()->user()->can(\"$permiso\")){ echo '<a href=\"'.$url.'\" class=\"$class\">'.$texto.'</a>'; }?>";
            }
        });

        Blade::directive('btnFile', function ($parametros) {
            $parametros_separados = explode(',', $parametros);
            $url = $parametros_separados[0];
            if (count($parametros_separados) == 2) {
                $url = $parametros_separados[0].', '.$parametros_separados[1];

                return "<?php echo '<a href=\"'.$url.'\" target=\"_blank\">'.iconoArchivo($url).'</a>'; ?>";
            } else {
                return "<?php echo '<a href=\"'.$url.'\" target=\"_blank\">'.iconoArchivo($url).'</a>'; ?>";
            }
        });
        /*asset(env('MINIO_URL').'/'.*/

        Blade::directive('btnFileMinio', function ($archivo) {
            /*return "<?php echo '<a href=\"'.asset(env('MINIO_URL')).'/'.$archivo.'\" target=\"_blank\">'.iconoArchivo($archivo).'</a>'; ?>"; */
            return "<?php echo btnFilMinio($archivo);?>";
        });

        Blade::directive('btnBack', function ($parametros) {
            $parametros_separados = explode(',', $parametros);
            $url = $parametros_separados[0];
            if (count($parametros_separados) == 2) {
                $url = $parametros_separados[0].', '.$parametros_separados[1];

                return "<?php echo '<a href=\"'.$url.'\"><i class=\"fa fa-arrow-alt-circle-left\"></i></a>'; ?>";
            } else {
                return "<?php echo '<a href=\"'.$url.'\"><i class=\"fa fa-arrow-alt-circle-left\"></i></a>'; ?>";
            }
        });

        Blade::directive('btnBackCan', function ($parametros) {
            $parametros_separados = explode(',', $parametros);
            $url = $parametros_separados[0];
            $url_permiso_primer_comilla = Str::after($parametros_separados[0], "'");
            $url_permiso_segunda_comilla = Str::before($url_permiso_primer_comilla, "'");
            $permiso = $url_permiso_segunda_comilla;
            if (count($parametros_separados) == 2) {
                $url = $parametros_separados[0].', '.$parametros_separados[1];

                return "<?php if(auth()->user()->can(\"$permiso\")){ echo '<a href=\"'.$url.'\"><i class=\"fa fa-arrow-alt-circle-left\"></i></a>'; }?>";
            } else {
                return "<?php if(auth()->user()->can(\"$permiso\")){ echo '<a href=\"'.$url.'\"><i class=\"fa fa-arrow-alt-circle-left\"></i></a>'; }?>";
            }
        });

        Builder::macro('whereLike', function ($field, $value) {
            //return $this->where($field,'LIKE',"%{$value}%");
            return $this->where(DB::raw("lower($field)"), 'like', '%'.strtolower($value).'%');
        });

        Builder::macro('orWhereLike', function ($field, $value) {
            return $this->orWhere(DB::raw("lower($field)"), 'like', '%'.strtolower($value).'%');
            //return $this->orWhere($field, 'LIKE', "%{$value}%");
        });

        Blade::directive('btnXml', function ($parametros) {
            $parametros_separados = explode(',', $parametros);
            $auditoria = $parametros_separados[0];
            $campo = $parametros_separados[1];

            return "<?php echo btnDownloadXml($auditoria, $campo);?>";
        });
    }
}

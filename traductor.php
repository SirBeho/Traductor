<?php
require 'vendor/autoload.php';

use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Smaller;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PreInc;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\While_;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Unset_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\PrettyPrinter\Standard;
use PhpParser\ParserFactory;


class CToJavaConverter
{
    private $parser;
    private $printer;

    public function __construct()
    {
        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $this->printer = new Standard();
    }

    public function convertCToJava($cCode)
    {

        return "hola";
        // Analizar el código C
        $cNodes = $this->parseCode($cCode);

        // Transformar los nodos C a nodos Java
        $javaNodes = $this->transformCToJava($cNodes);

        // Imprimir el código Java resultante
        return $this->printCode($javaNodes);
    }

    private function parseCode($code)
    {
        try {
            return $this->parser->parse($code);
        } catch (Error $e) {
            echo 'Error: ', $e->getMessage();
            return null;
        }
    }

    private function printCode($nodes)
    {
        return $this->printer->prettyPrintFile($nodes);
    }

    private function transformCToJava($cNodes)
    {
        // Implementa la lógica de transformación de C a Java
        $this->traverseNodes($cNodes, function ($node) {
            // Cambiar declaraciones de variables
            if ($node instanceof Node\Stmt\Declare_) {
                $this->transformVariableDeclaration($node);
            }

            // Cambiar funciones if
            if ($node instanceof Node\Stmt\If_) {
                $this->transformIfStatement($node);
            }

            // Cambiar funciones while
            if ($node instanceof Node\Stmt\While_) {
                $this->transformWhileStatement($node);
            }

            // Cambiar funciones for
            if ($node instanceof Node\Stmt\For_) {
                $this->transformForStatement($node);
            }

            // Cambiar funciones do-while
            if ($node instanceof Node\Stmt\Do_) {
                $this->transformDoWhileStatement($node);
            }

            // Cambiar "printf" por "System.out.println"
            if ($node instanceof Node\Expr\FuncCall &&
                $node->name instanceof Node\Name &&
                $node->name->parts == ['printf']) {
                $this->transformPrintfFunction($node);
            }
        });

        return $cNodes;
    }

    private function traverseNodes(&$nodes, $callback)
    {
        foreach ($nodes as &$node) {
            if ($node instanceof Node) {
                $callback($node);
                $this->traverseNodes($node, $callback);
            } elseif (is_array($node)) {
                $this->traverseNodes($node, $callback);
            }
        }
    }

    private function transformVariableDeclaration(Declare_ $declare)
    {
        foreach ($declare->declares as $declaration) {
            if ($declaration->var instanceof Node\Expr\Variable) {
                $declaration->var->name = ucfirst($declaration->var->name);
        
                // Cambia el tipo de datos según las convenciones de Java
                if ($declaration->type instanceof Node\Name) {
                    $declaration->type = new Node\Name($this->mapCTypeToJava($declaration->type->toString()));
                }
            }
        }
    }

    private function transformIfStatement(If_ $ifStatement)
    {
        // Agrega un bloque vacío para el caso en que no hay un bloque "else"
        if (!$ifStatement->else && !$ifStatement->elseifs) {
            $ifStatement->stmts[] = new Node\Stmt\Nop();
        }
    }

    private function transformWhileStatement(While_ $whileStatement)
    {
        // Agrega un bloque vacío si no hay un bloque dentro del bucle
        if (!$whileStatement->stmts) {
            $whileStatement->stmts[] = new Node\Stmt\Nop();
        }
    }

    private function transformForStatement(For_ $forStatement)
    {
        // Agrega un bloque vacío si no hay un bloque dentro del bucle
        if (!$forStatement->stmts) {
            $forStatement->stmts[] = new Node\Stmt\Nop();
        }
    }

    private function transformDoWhileStatement(Do_ $doWhileStatement)
    {
        // Agrega un bloque vacío si no hay un bloque dentro del bucle
        if (!$doWhileStatement->stmts) {
            $doWhileStatement->stmts[] = new Node\Stmt\Nop();
        }
    }

    private function transformPrintfFunction(Node\Expr\FuncCall $printfFunction)
    {
        $printfFunction->name->parts = ['System', 'out', 'println'];

        // Transforma los argumentos de printf
        $printfFunction->args = array_map(function ($arg) {
            if ($arg->value instanceof String_) {
                return new Node\Arg(
                    new Node\Expr\FuncCall(
                        new Node\Name('String.format'),
                        [new Node\Arg(new String_($arg->value->value))]
                    )
                );
            }

            return $arg;
        }, $printfFunction->args);
    }

    private function mapCTypeToJava($cType)
    {
        // Mapea tipos de datos de C a Java
        $typeMap = [
            'int' => 'Integer',
            'float' => 'Float',
            'double' => 'Double',
            'char' => 'Character',
            'short' => 'Short',
            'long' => 'Long',
        ];

        return $typeMap[$cType] ?? $cType;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $codigoC = $_POST['texto'];

    $converter = new CToJavaConverter();
    $javaCode = $converter->convertCToJava($codigoC);

    echo $javaCode;
}

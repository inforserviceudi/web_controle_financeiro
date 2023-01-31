function mascara(o,f){
  v_obj = o;
  v_fun = f;
  setTimeout("execmascara()", 1);
}
function execmascara(){
  v_obj.value = v_fun(v_obj.value);
}
function mtel(v){
  v=v.replace(/\D/g,"");
  v=v.replace(/^(\d{2})(\d)/g,"($1) $2");
  v=v.replace(/(\d)(\d{4})$/,"$1-$2");
  return v;
}
function mtelefone(v){
  v=v.replace(/\D/g,"");
  v=v.replace(/(\d)(\d{4})$/,"$1-$2");
  return v;
}
function mcnpj(v){
  v = v.replace(/\D/g, "");                           //Remove tudo o que nÃ£o Ã© dÃgito
  v = v.replace(/^(\d{2})(\d)/, "$1.$2");             //Coloca ponto entre o segundo e o terceiro dÃgitos
  v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3"); //Coloca ponto entre o quinto e o sexto dÃgitos
  v = v.replace(/\.(\d{3})(\d)/, ".$1/$2");           //Coloca uma barra entre o oitavo e o nono dÃgitos
  v = v.replace(/(\d{4})(\d)/, "$1-$2");              //Coloca um hÃfen depois do bloco de quatro dÃgitos
  return v;
}
function mcpf(v){
  v = v.replace(/\D/g, "");                    //Remove tudo o que nÃ£o Ã© dÃgito
  v = v.replace(/(\d{3})(\d)/, "$1.$2");       //Coloca um ponto entre o terceiro e o quarto dÃgitos
  v = v.replace(/(\d{3})(\d)/, "$1.$2");       //Coloca um ponto entre o terceiro e o quarto dÃgitos
                                           //de novo (para o segundo bloco de nÃºmeros)
  v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2"); //Coloca um hÃfen entre o terceiro e o quarto dÃgitos
  return v;
}

function mproduto(v){
  v = v.replace(/\D/g, "");                    //Remove tudo o que nÃ£o Ã© dÃgito
  v = v.replace(/(\d{3})(\d)/, "$1.$2");       //Coloca um ponto entre o terceiro e o quarto dÃgitos
  return v;
}

function mcep(v){
  v = v.replace(/\D/g, "");
  v = v.replace(/^(\d{5})(\d)/, "$1-$2");
  return v;
}
function msite(v){
  v = v.replace(/^http:\/\/?/, "");
  dominio = v;
  caminho = "";
  if (v.indexOf("/") > -1)
      dominio = v.split("/")[0];
      caminho = v.replace(/[^\/]*/, "");
      dominio = dominio.replace(/[^\w\.\+-:@]/g, "");
      caminho = caminho.replace(/[^\w\d\+-@:\?&=%\(\)\.]/g, "");
      caminho = caminho.replace(/([\?&])=/, "$1");
  if (caminho != "") dominio = dominio.replace(/\.+$/, "");
  v = "http://" + dominio + caminho;
  return v;
}
function mdinheiro(v) {
v=v.replace(/\D/g,"")
  v = v.replace(/(\d{1})(\d{14})$/, "$1.$2");
  v = v.replace(/(\d{1})(\d{11})$/, "$1.$2");
  v = v.replace(/(\d{1})(\d{8})$/, "$1.$2");
  v = v.replace(/(\d{1})(\d{5})$/, "$1.$2");
v=v.replace(/(\d{1})(\d{1,2})$/,"$1,$2");
return v;
}

function mdata(v){
  v=v.replace(/\D/g,"");
  v=v.replace(/(\d{2})(\d)/,"$1/$2");
  v=v.replace(/(\d{2})(\d)/,"$1/$2");
  v=v.replace(/(\d{4})(\d)/,"$1/$2");
  return v;
}
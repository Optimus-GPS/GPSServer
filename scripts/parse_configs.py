import os


FILES = ['/var/www/html/config.php']


def parse_templates(tmpl_lines, file_result):
    lines_result = []
    for line in tmpl_lines:
        index1 = line.find('${')
        if index1 == -1:
            lines_result.append(line)
            continue
        line = parse_line(line=line, index1=index1)
        lines_result.append(line)
    with open(file_result, 'w') as result_file:
        result_file.writelines(lines_result)


def parse_line(line, index1):
    index2 = line.find('}', index1)
    var = line[index1 + 2:index2]
    full_var = '${' + var + '}'
    value = os.getenv(var.upper().replace('.', '_'), full_var).replace('\n', '')
    line = line.replace(full_var, value)
    index1 = line.find('${', index1 + 1)
    if index1 != -1:
        line = parse_line(line=line, index1=index1)
    return line


def process_tmpls():
    """ Parse templates in root_dir folder """
    for config_file in FILES:
        with open(config_file) as xfiles:
            tfile = xfiles.readlines()
        parse_templates(tmpl_lines=tfile, file_result=config_file)
        print('{} procesado!!!'.format(config_file))


print('Procesando archivos de configuracion ...')
process_tmpls()

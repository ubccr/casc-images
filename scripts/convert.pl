use strict;

while(<>) {
    chomp;

    my $from = $_;
    my $thumb = $from;
    $thumb =~ s/\.\w+$/\.png/;
    $thumb =~ s/\/raw\//\/180x\//;

    my $full = $from;
    $full =~ s/\.\w+$/\.png/;
    $full =~ s/\/raw\//\/600x\//;

    unless(-e $thumb) {
        system("convert -resize 180x180 $from $thumb");
    }

    unless(-e $full) {
        system("convert -resize 600x $from $full");
    }
}

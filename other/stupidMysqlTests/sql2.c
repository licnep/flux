#include <stdio.h>
#include <stdlib.h>

/*
  This file generates the sql command to populate the 'routing' table.
  Change 'total' if you want more rows.
  Right now simply, for each flux there's a rule to send 100% to the next flux.
*/

int main(int argc, char* argv) {
  int i;
  int total = 10;
int g = 0, n=0;
  printf("INSERT INTO routing\n(flux_from_id,flux_to_id,share)\nVALUES\n");
  for (i=0;i<total;i++) {
    n = n+10%total;
    //printf("(%d,%d,%d)",n,g%total,(int)random()%100);
    printf("(%d,%d,%d)",1+i%total,1+(i+1)%total,100);
    g++;
    if (i!=total-1) printf(",\n");
    else printf("\n");
  }
  printf(";\n");
  return 0;
}


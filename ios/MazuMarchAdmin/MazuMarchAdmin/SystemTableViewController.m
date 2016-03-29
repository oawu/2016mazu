//
//  SystemTableViewController.m
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/29.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "SystemTableViewController.h"

@interface SystemTableViewController ()

@end

@implementation SystemTableViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    
//    [self.tableView setSeparatorStyle:UITableViewCellSeparatorStyleNone];
    [self.tableView setBackgroundView:nil];
    [self.tableView setBackgroundColor:[UIColor colorWithRed:0.94 green:0.94 blue:0.96 alpha:1]];
    [self.tableView.layer setBackgroundColor:[UIColor colorWithRed:0.94 green:0.94 blue:0.96 alpha:1].CGColor];

    
    
    
    self.features = [NSMutableArray new];
    [self.features addObject:@{
                          @"name": @"管理系統",
                          @"items": @[
                                  @{
                                      @"name": @"活動選擇",
                                      @"action": @"AvatarViewController",
                                      @"image": @"system_00"
                                      },
                                  @{
                                      @"name": @"清除暫存",
                                      @"action": @"NameViewController",
                                      @"image": @"system_01"
                                      },
                                  @{
                                      @"name": @"黑名單列表",
                                      @"action": @"NameViewController",
                                      @"image": @"system_02"
                                      }
                                  ]
                          }];
    [self.features addObject:@{
                          @"name": @"定位系統",
                          @"items": @[
                                  @{
                                      @"name": @"GPS電量",
                                      @"action": @"AvatarViewController",
                                      @"image": @"system_100"
                                      },
                                  @{
                                      @"name": @"上次上傳",
                                      @"action": @"AvatarViewController",
                                      @"image": @"system_11"
                                      }]
                          }];
    
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

#pragma mark - Table view data source

- (CGFloat)tableView:(UITableView *)tableView heightForHeaderInSection:(NSInteger)section {
    return 35;
}
- (CGFloat)tableView:(UITableView *)tableView heightForFooterInSection:(NSInteger)section {
    return 0.000001f;
}
- (NSString *)tableView:(UITableView *)tableView titleForHeaderInSection:(NSInteger)section {

    return [[self.features objectAtIndex:section] objectForKey:@"name"];
}
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return [self.features count];
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return [[[self.features objectAtIndex:section] objectForKey:@"items"] count];
}


- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:@"cel" forIndexPath:indexPath];

    NSString *text = [[[[self.features objectAtIndex:indexPath.section] objectForKey:@"items"] objectAtIndex:indexPath.row] objectForKey:@"name"];
    NSString *action = [[[[self.features objectAtIndex:indexPath.section] objectForKey:@"items"] objectAtIndex:indexPath.row] objectForKey:@"action"];
    NSString *image = [[[[self.features objectAtIndex:indexPath.section] objectForKey:@"items"] objectAtIndex:indexPath.row] objectForKey:@"image"];
    
    [cell.textLabel setText:text];
    [cell.imageView setImage:[UIImage imageNamed:image]];
    
    return cell;
}


/*
// Override to support conditional editing of the table view.
- (BOOL)tableView:(UITableView *)tableView canEditRowAtIndexPath:(NSIndexPath *)indexPath {
    // Return NO if you do not want the specified item to be editable.
    return YES;
}
*/

/*
// Override to support editing the table view.
- (void)tableView:(UITableView *)tableView commitEditingStyle:(UITableViewCellEditingStyle)editingStyle forRowAtIndexPath:(NSIndexPath *)indexPath {
    if (editingStyle == UITableViewCellEditingStyleDelete) {
        // Delete the row from the data source
        [tableView deleteRowsAtIndexPaths:@[indexPath] withRowAnimation:UITableViewRowAnimationFade];
    } else if (editingStyle == UITableViewCellEditingStyleInsert) {
        // Create a new instance of the appropriate class, insert it into the array, and add a new row to the table view
    }   
}
*/

/*
// Override to support rearranging the table view.
- (void)tableView:(UITableView *)tableView moveRowAtIndexPath:(NSIndexPath *)fromIndexPath toIndexPath:(NSIndexPath *)toIndexPath {
}
*/

/*
// Override to support conditional rearranging of the table view.
- (BOOL)tableView:(UITableView *)tableView canMoveRowAtIndexPath:(NSIndexPath *)indexPath {
    // Return NO if you do not want the item to be re-orderable.
    return YES;
}
*/

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
